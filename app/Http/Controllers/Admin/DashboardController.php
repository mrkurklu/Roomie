<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Message;
use App\Models\User;
use App\Models\Room;
use App\Models\Ticket;
use App\Models\Feedback;
use App\Models\Request as GuestRequest;
use App\Models\Event;
use App\Models\GuestStay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $hotelId = $user->hotel_id;

        // Eğer hotel_id yoksa boş veri döndür
        if (!$hotelId) {
            return view('admin.dashboard', [
                'role' => 'Yönetim',
                'activeTab' => 'dashboard',
                'stats' => [],
                'recentTasks' => collect([]),
                'recentMessages' => collect([]),
            ]);
        }

        // İstatistikler
        $totalRooms = Room::where('hotel_id', $hotelId)->count();
        $occupiedRooms = Room::where('hotel_id', $hotelId)->where('status', 'occupied')->count();
        $occupancyRate = $totalRooms > 0 ? round(($occupiedRooms / $totalRooms) * 100, 1) : 0;
        
        // Son 30 gün içindeki yeni rezervasyonlar (misafir kullanıcılar)
        $newBookings = User::where('hotel_id', $hotelId)
            ->whereHas('roles', function($q) {
                $q->where('name', 'misafir');
            })
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->count();
        
        // Bugün check-in yapan misafirler (son 24 saat içinde oluşturulan)
        $guestCheckins = User::where('hotel_id', $hotelId)
            ->whereHas('roles', function($q) {
                $q->where('name', 'misafir');
            })
            ->where('created_at', '>=', Carbon::today())
            ->count();
        
        // Son 30 gün içindeki mesaj sayısı
        $monthMessages = Message::where('hotel_id', $hotelId)
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->count();
        
        // Son ay içindeki mesaj sayısı (önceki ay ile karşılaştırma için)
        $previousMonthMessages = Message::where('hotel_id', $hotelId)
            ->whereBetween('created_at', [
                Carbon::now()->subDays(60),
                Carbon::now()->subDays(30)
            ])
            ->count();
        
        $messageGrowth = $previousMonthMessages > 0 
            ? round((($monthMessages - $previousMonthMessages) / $previousMonthMessages) * 100, 1)
            : ($monthMessages > 0 ? 100 : 0);
        
        // Önceki ay doluluk oranı
        $previousMonthOccupied = Room::where('hotel_id', $hotelId)
            ->where('status', 'occupied')
            ->count(); // Basit bir yaklaşım, gerçekte tarih bazlı olmalı
        
        $occupancyGrowth = 0; // Basit bir yaklaşım
        
        $stats = [
            'total_tasks' => Task::where('hotel_id', $hotelId)->count(),
            'pending_tasks' => Task::where('hotel_id', $hotelId)->where('status', 'pending')->count(),
            'completed_tasks' => Task::where('hotel_id', $hotelId)->where('status', 'completed')->count(),
            'total_messages' => Message::where('hotel_id', $hotelId)->count(),
            'unread_messages' => Message::where('hotel_id', $hotelId)->where(function($q) {
                $q->whereNull('is_read')->orWhere('is_read', false);
            })->count(),
            'total_guests' => User::where('hotel_id', $hotelId)->whereHas('roles', function($q) {
                $q->where('name', 'misafir');
            })->count(),
            'total_rooms' => $totalRooms,
            'available_rooms' => Room::where('hotel_id', $hotelId)->where('status', 'available')->count(),
            'occupied_rooms' => $occupiedRooms,
            'occupancy_rate' => $occupancyRate,
            'occupancy_growth' => $occupancyGrowth,
            'new_bookings' => $newBookings,
            'guest_checkins' => $guestCheckins,
            'total_revenue' => 0, // Revenue için ayrı bir tablo/model gerekli
            'month_revenue' => 0, // Revenue için ayrı bir tablo/model gerekli
            'message_growth' => $messageGrowth,
        ];
        
        // Son aktiviteler (gerçek veriler)
        $recentActivity = collect();
        
        // Son mesajlar
        $recentMessagesForActivity = Message::where('hotel_id', $hotelId)
            ->with(['fromUser', 'toUser'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function($message) {
                return [
                    'icon' => 'message-square',
                    'iconColor' => 'text-blue-500',
                    'color' => 'bg-blue-500/20',
                    'message' => ($message->fromUser->name ?? 'Bilinmeyen') . ' bir mesaj gönderdi',
                    'time' => $message->created_at->diffForHumans(),
                    'timestamp' => $message->created_at,
                ];
            });
        
        // Son görevler
        $recentTasksForActivity = Task::where('hotel_id', $hotelId)
            ->with(['assignedTo', 'createdBy'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function($task) {
                $statusColors = [
                    'pending' => ['icon' => 'clock', 'iconColor' => 'text-yellow-500', 'color' => 'bg-yellow-500/20'],
                    'in_progress' => ['icon' => 'loader', 'iconColor' => 'text-blue-500', 'color' => 'bg-blue-500/20'],
                    'completed' => ['icon' => 'check', 'iconColor' => 'text-green-500', 'color' => 'bg-green-500/20'],
                ];
                $statusInfo = $statusColors[$task->status] ?? $statusColors['pending'];
                
                return [
                    'icon' => $statusInfo['icon'],
                    'iconColor' => $statusInfo['iconColor'],
                    'color' => $statusInfo['color'],
                    'message' => 'Görev: ' . $task->title . ' (' . ucfirst($task->status) . ')',
                    'time' => $task->created_at->diffForHumans(),
                    'timestamp' => $task->created_at,
                ];
            });
        
        // Mesaj ve görevleri birleştir ve tarihe göre sırala
        $recentActivity = collect()
            ->merge($recentMessagesForActivity)
            ->merge($recentTasksForActivity)
            ->sortByDesc('timestamp')
            ->take(5)
            ->map(function($item) {
                unset($item['timestamp']);
                return $item;
            })
            ->values();

        // Son görevler
        $recentTasks = Task::where('hotel_id', $hotelId)
            ->with(['assignedTo', 'createdBy'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Son mesajlar
        $recentMessages = Message::where('hotel_id', $hotelId)
            ->with(['fromUser', 'toUser'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', [
            'role' => 'Yönetim',
            'activeTab' => 'dashboard',
            'stats' => $stats,
            'recentTasks' => $recentTasks,
            'recentMessages' => $recentMessages,
            'recentActivity' => $recentActivity,
        ]);
    }
    
    public function tasks()
    {
        $user = auth()->user();
        $hotelId = $user->hotel_id;

        $tasks = Task::where('hotel_id', $hotelId)
            ->with(['assignedTo', 'createdBy'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $stats = [
            'total' => Task::where('hotel_id', $hotelId)->count(),
            'pending' => Task::where('hotel_id', $hotelId)->where('status', 'pending')->count(),
            'in_progress' => Task::where('hotel_id', $hotelId)->where('status', 'in_progress')->count(),
            'completed' => Task::where('hotel_id', $hotelId)->where('status', 'completed')->count(),
        ];

        return view('admin.tasks', [
            'role' => 'Yönetim',
            'activeTab' => 'tasks',
            'tasks' => $tasks,
            'stats' => $stats,
        ]);
    }
    
    public function messages(Request $request)
    {
        $user = auth()->user();
        $hotelId = $user->hotel_id;

        // Belirli bir kullanıcıyla chat thread'i için to_user_id parametresi
        $toUserId = $request->get('to_user_id');
        
        if ($toUserId) {
            // Belirli bir kullanıcıyla olan mesajları getir
            $user = auth()->user();
            $messages = Message::where('hotel_id', $hotelId)
                ->where(function($query) use ($user, $toUserId) {
                    $query->where(function($q) use ($user, $toUserId) {
                        $q->where('sender_id', $user->id)
                          ->where('to_user_id', $toUserId);
                    })->orWhere(function($q) use ($user, $toUserId) {
                        $q->where('sender_id', $toUserId)
                          ->where('to_user_id', $user->id);
                    });
                })
                ->with(['fromUser', 'toUser'])
                ->orderBy('created_at', 'asc')
                ->get();
        } else {
            // Tüm mesajları getir (en son mesajlaşan kullanıcıları göster)
            $user = auth()->user();
            $messages = Message::where('hotel_id', $hotelId)
                ->where(function($query) use ($user) {
                    $query->where('sender_id', $user->id)
                          ->orWhere('to_user_id', $user->id);
                })
                ->with(['fromUser', 'toUser'])
                ->orderBy('created_at', 'desc')
                ->get()
                ->unique(function($message) use ($user) {
                    $senderId = $message->attributes['sender_id'] ?? $message->from_user_id;
                    if ($senderId == $user->id) {
                        return $message->to_user_id;
                    } else {
                        return $senderId;
                    }
                })
                ->take(20);
        }
        
        // Mesajları kullanıcının diline göre çevir
        $userLanguage = auth()->user()->language ?? 'tr';
        $messages = $messages->transform(function($message) use ($userLanguage, $user) {
            $senderId = $message->attributes['sender_id'] ?? $message->from_user_id;
            if ($senderId == $user->id) {
                $message->display_content = $message->original_content ?? $message->content;
            } else {
                if ($message->translated_content && $message->original_language !== $userLanguage) {
                    $message->display_content = \App\Services\TranslationService::translate(
                        $message->original_content ?? $message->content,
                        $userLanguage,
                        $message->original_language ?? 'tr'
                    );
                } else {
                    $message->display_content = $message->content;
                }
            }
            return $message;
        });

        $stats = [
            'total' => Message::where('hotel_id', $hotelId)->count(),
            'unread' => Message::where('hotel_id', $hotelId)->where(function($q) {
                $q->whereNull('is_read')->orWhere('is_read', false);
            })->count(),
            'internal' => Message::where('hotel_id', $hotelId)->where('type', 'internal')->count(),
            'guest' => Message::where('hotel_id', $hotelId)->where('type', 'guest')->count(),
        ];

        // Chat thread için kullanıcı bilgisi
        $chatUser = $toUserId ? \App\Models\User::find($toUserId) : null;

        return view('admin.messages', [
            'role' => 'Yönetim',
            'activeTab' => 'messages',
            'messages' => $messages,
            'stats' => $stats,
            'chatUser' => $chatUser,
            'toUserId' => $toUserId,
        ]);
    }
    
    public function guests()
    {
        $user = auth()->user();
        $hotelId = $user->hotel_id;

        // Debug: Tüm misafirleri kontrol et
        $allGuestsDebug = User::where('hotel_id', $hotelId)->get();
        \Log::info('Tüm kullanıcılar (hotel_id: ' . $hotelId . ')', [
            'total_users' => $allGuestsDebug->count(),
            'users_with_roles' => $allGuestsDebug->map(function($u) {
                return [
                    'id' => $u->id,
                    'name' => $u->name,
                    'roles' => $u->roles->pluck('name')->toArray(),
                ];
            })->toArray(),
        ]);

        // Aktif misafir kalışlarını ayrı yükle
        $activeStays = GuestStay::where('hotel_id', $hotelId)
            ->where('status', 'checked_in')
            ->with('room.roomType', 'room.assignedStaff')
            ->get()
            ->keyBy('user_id');
        
        $guests = User::where('hotel_id', $hotelId)
            ->whereHas('roles', function($q) {
                $q->whereIn('name', ['misafir', 'guest']);
            })
            ->with(['roles'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        // Her misafire aktif kalışını ekle
        foreach ($guests as $guest) {
            if (isset($activeStays[$guest->id])) {
                $guest->setRelation('activeGuestStay', $activeStays[$guest->id]);
            }
        }
        
        \Log::info('Misafirler sorgusu sonucu', [
            'total_guests' => $guests->total(),
            'current_page' => $guests->currentPage(),
            'active_stays_count' => $activeStays->count(),
        ]);

        $activeGuests = GuestStay::where('hotel_id', $hotelId)
            ->where('status', 'checked_in')
            ->count();

        $stats = [
            'total' => User::where('hotel_id', $hotelId)->whereHas('roles', function($q) {
                $q->whereIn('name', ['misafir', 'guest']);
            })->count(),
            'active' => $activeGuests,
        ];

        $rooms = Room::where('hotel_id', $hotelId)
            ->with('roomType')
            ->orderBy('room_number')
            ->get();

        $staffMembers = User::where('hotel_id', $hotelId)
            ->whereHas('roles', function($q) {
                $q->where('name', 'personel');
            })
            ->get();

        return view('admin.guests', [
            'role' => 'Yönetim',
            'activeTab' => 'guests',
            'guests' => $guests,
            'stats' => $stats,
            'rooms' => $rooms,
            'staffMembers' => $staffMembers,
        ]);
    }

    public function checkIn(Request $request)
    {
        $user = auth()->user();
        $hotelId = $user->hotel_id;

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'room_id' => 'required|exists:rooms,id',
            'check_in' => 'required|date',
            'notes' => 'nullable|string|max:1000',
        ]);

        try {
            // Oda müsait mi kontrol et
            $room = Room::findOrFail($validated['room_id']);
            if ($room->hotel_id != $hotelId) {
                return redirect()->back()->with('error', 'Bu oda bu otele ait değil.');
            }

            // Misafirin aktif bir rezervasyonu var mı kontrol et
            $activeStay = GuestStay::where('user_id', $validated['user_id'])
                ->where('status', 'checked_in')
                ->first();

            if ($activeStay) {
                return redirect()->back()->with('error', 'Bu misafirin zaten aktif bir rezervasyonu var.');
            }

            // Oda dolu mu kontrol et
            $roomOccupied = GuestStay::where('room_id', $validated['room_id'])
                ->where('status', 'checked_in')
                ->exists();

            if ($roomOccupied) {
                return redirect()->back()->with('error', 'Bu oda şu anda dolu.');
            }

            // Check-in oluştur
            $guestStay = GuestStay::create([
                'hotel_id' => $hotelId,
                'user_id' => $validated['user_id'],
                'room_id' => $validated['room_id'],
                'check_in' => $validated['check_in'],
                'status' => 'checked_in',
                'notes' => $validated['notes'] ?? null,
                'checked_in_by' => $user->id,
            ]);

            // Oda durumunu güncelle
            $room->status = 'occupied';
            $room->save();

            return redirect()->back()->with('success', 'Check-in başarıyla yapıldı.');
        } catch (\Exception $e) {
            \Log::error('Check-in hatası: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Check-in yapılırken bir hata oluştu: ' . $e->getMessage());
        }
    }

    public function checkOut(Request $request, GuestStay $guestStay)
    {
        $user = auth()->user();
        $hotelId = $user->hotel_id;

        $validated = $request->validate([
            'check_out' => 'required|date',
            'notes' => 'nullable|string|max:1000',
        ]);

        try {
            if ($guestStay->hotel_id != $hotelId) {
                return redirect()->back()->with('error', 'Bu rezervasyon bu otele ait değil.');
            }

            if ($guestStay->status == 'checked_out') {
                return redirect()->back()->with('error', 'Bu rezervasyon zaten check-out yapılmış.');
            }

            // Check-out yap
            $guestStay->check_out = $validated['check_out'];
            $guestStay->status = 'checked_out';
            $guestStay->checked_out_by = $user->id;
            if ($validated['notes']) {
                $guestStay->notes = ($guestStay->notes ? $guestStay->notes . "\n" : '') . $validated['notes'];
            }
            $guestStay->save();

            // Oda durumunu güncelle
            $room = $guestStay->room;
            $room->status = 'available';
            $room->save();

            return redirect()->back()->with('success', 'Check-out başarıyla yapıldı.');
        } catch (\Exception $e) {
            \Log::error('Check-out hatası: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Check-out yapılırken bir hata oluştu: ' . $e->getMessage());
        }
    }

    public function rooms(Request $request)
    {
        $user = auth()->user();
        $hotelId = $user->hotel_id;

        if (!$hotelId) {
            return view('admin.rooms', [
                'role' => 'Yönetim',
                'activeTab' => 'rooms',
                'rooms' => collect([]),
                'staffMembers' => collect([]),
                'roomTypes' => collect([]),
            ]);
        }

        // Arama ve filtreleme
        $search = $request->get('search', '');
        $statusFilter = $request->get('status', '');
        
        // Aktif misafir kalışlarını ayrı yükle
        $activeStays = GuestStay::where('hotel_id', $hotelId)
            ->where('status', 'checked_in')
            ->with('user')
            ->get()
            ->keyBy('room_id');
        
        $query = Room::where('hotel_id', $hotelId)
            ->with(['roomType', 'assignedStaff', 'guestStays.user']);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('room_number', 'like', '%' . $search . '%')
                  ->orWhereHas('roomType', function($q) use ($search) {
                      $q->where('name', 'like', '%' . $search . '%');
                  });
            });
        }

        if ($statusFilter) {
            $query->where('status', $statusFilter);
        }

        $rooms = $query->orderBy('room_number', 'asc')->get();
        
        // Her odaya aktif misafir kalışını ekle
        foreach ($rooms as $room) {
            if (isset($activeStays[$room->id])) {
                $room->setRelation('activeGuestStay', $activeStays[$room->id]);
            }
        }

        $staffMembers = User::where('hotel_id', $hotelId)
            ->whereHas('roles', function($q) {
                $q->where('name', 'personel');
            })
            ->get();

        $roomTypes = \App\Models\RoomType::where('hotel_id', $hotelId)->get();

        $stats = [
            'total' => Room::where('hotel_id', $hotelId)->count(),
            'available' => Room::where('hotel_id', $hotelId)->where('status', 'available')->count(),
            'occupied' => Room::where('hotel_id', $hotelId)->where('status', 'occupied')->count(),
            'maintenance' => Room::where('hotel_id', $hotelId)->where('status', 'maintenance')->count(),
        ];

        return view('admin.rooms', [
            'role' => 'Yönetim',
            'activeTab' => 'rooms',
            'rooms' => $rooms,
            'staffMembers' => $staffMembers,
            'roomTypes' => $roomTypes,
            'stats' => $stats,
            'search' => $search,
            'statusFilter' => $statusFilter,
        ]);
    }

    public function createRooms(Request $request)
    {
        $user = auth()->user();
        $hotelId = $user->hotel_id;

        $validated = $request->validate([
            'room_count' => 'required|integer|min:1|max:500',
            'room_type_id' => 'required|exists:room_types,id',
            'start_number' => 'nullable|integer|min:1',
            'prefix' => 'nullable|string|max:10',
        ]);

        try {
            $roomType = \App\Models\RoomType::findOrFail($validated['room_type_id']);
            if ($roomType->hotel_id != $hotelId) {
                return redirect()->back()->with('error', 'Bu oda tipi bu otele ait değil.');
            }

            $startNumber = (int)($validated['start_number'] ?? 1);
            $prefix = $validated['prefix'] ?? '';
            $createdCount = 0;
            $currentNumber = $startNumber;

            // Mevcut oda numaralarını al
            $existingNumbers = Room::where('hotel_id', $hotelId)
                ->pluck('room_number')
                ->toArray();

            for ($i = 0; $i < $validated['room_count']; $i++) {
                // Oda numarasını oluştur: prefix + numara
                $roomNumber = $prefix . $currentNumber;
                
                // Eğer bu numara zaten varsa, bir sonrakini dene
                while (in_array($roomNumber, $existingNumbers)) {
                    $currentNumber++;
                    $roomNumber = $prefix . $currentNumber;
                }

                Room::create([
                    'hotel_id' => $hotelId,
                    'room_type_id' => $validated['room_type_id'],
                    'room_number' => $roomNumber,
                    'status' => 'available',
                ]);

                $existingNumbers[] = $roomNumber;
                $createdCount++;
                
                // Bir sonraki oda için numarayı artır
                // Örnek: prefix="a", startNumber=101 -> a101, a102, a103...
                $currentNumber++;
            }

            return redirect()->back()->with('success', $createdCount . ' oda başarıyla oluşturuldu.');
        } catch (\Exception $e) {
            \Log::error('Oda oluşturma hatası: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Odalar oluşturulurken bir hata oluştu: ' . $e->getMessage());
        }
    }

    public function updateRoom(Request $request, Room $room)
    {
        $user = auth()->user();
        $hotelId = $user->hotel_id;

        if ($room->hotel_id != $hotelId) {
            return redirect()->back()->with('error', 'Bu oda bu otele ait değil.');
        }

        $validated = $request->validate([
            'room_number' => 'required|string|max:50',
            'room_type_id' => 'required|exists:room_types,id',
            'status' => 'required|in:available,occupied,maintenance',
        ]);

        try {
            // Oda numarası benzersizliğini kontrol et (kendi hariç)
            $existingRoom = Room::where('room_number', $validated['room_number'])
                ->where('id', '!=', $room->id)
                ->first();

            if ($existingRoom) {
                return redirect()->back()->with('error', 'Bu oda numarası zaten kullanılıyor.');
            }

            $room->room_number = $validated['room_number'];
            $room->room_type_id = $validated['room_type_id'];
            $room->status = $validated['status'];
            $room->save();

            return redirect()->back()->with('success', 'Oda bilgileri başarıyla güncellendi.');
        } catch (\Exception $e) {
            \Log::error('Oda güncelleme hatası: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Oda güncellenirken bir hata oluştu: ' . $e->getMessage());
        }
    }

    public function createRoomType(Request $request)
    {
        $user = auth()->user();
        $hotelId = $user->hotel_id;

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'price_per_night' => 'required|numeric|min:0',
            'capacity' => 'required|integer|min:1|max:20',
        ]);

        try {
            \App\Models\RoomType::create([
                'hotel_id' => $hotelId,
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'price_per_night' => $validated['price_per_night'],
                'capacity' => $validated['capacity'],
            ]);

            return redirect()->back()->with('success', 'Oda tipi başarıyla oluşturuldu.');
        } catch (\Exception $e) {
            \Log::error('Oda tipi oluşturma hatası: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Oda tipi oluşturulurken bir hata oluştu: ' . $e->getMessage());
        }
    }

    public function updateRoomType(Request $request, \App\Models\RoomType $roomType)
    {
        $user = auth()->user();
        $hotelId = $user->hotel_id;

        if ($roomType->hotel_id != $hotelId) {
            return redirect()->back()->with('error', 'Bu oda tipi bu otele ait değil.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'price_per_night' => 'required|numeric|min:0',
            'capacity' => 'required|integer|min:1|max:20',
        ]);

        try {
            $roomType->update($validated);
            return redirect()->back()->with('success', 'Oda tipi başarıyla güncellendi.');
        } catch (\Exception $e) {
            \Log::error('Oda tipi güncelleme hatası: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Oda tipi güncellenirken bir hata oluştu: ' . $e->getMessage());
        }
    }

    public function deleteRoomType($roomTypeId)
    {
        $user = auth()->user();
        $hotelId = $user->hotel_id;

        try {
            $roomType = \App\Models\RoomType::findOrFail($roomTypeId);
            
            if ($roomType->hotel_id != $hotelId) {
                return redirect()->back()->with('error', 'Bu oda tipi bu otele ait değil.');
            }

            // Bu oda tipine ait odaları kontrol et
            $rooms = $roomType->rooms;
            $roomCount = $rooms->count();
            
            // Eğer odalarda aktif misafir varsa silme
            foreach ($rooms as $room) {
                $activeStay = GuestStay::where('room_id', $room->id)
                    ->where('status', 'checked_in')
                    ->first();
                
                if ($activeStay) {
                    return redirect()->back()->with('error', 'Bu oda tipine ait odalarda aktif misafir var. Önce check-out yapın.');
                }
            }

            // Önce tüm odaları sil
            foreach ($rooms as $room) {
                $room->delete();
            }

            // Sonra oda tipini sil
            $roomType->delete();
            
            $message = 'Oda tipi başarıyla silindi.';
            if ($roomCount > 0) {
                $message .= ' ' . $roomCount . ' oda da silindi.';
            }
            
            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            \Log::error('Oda tipi silme hatası: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Oda tipi silinirken bir hata oluştu: ' . $e->getMessage());
        }
    }

    public function deleteRoom(Room $room)
    {
        $user = auth()->user();
        $hotelId = $user->hotel_id;

        if ($room->hotel_id != $hotelId) {
            return redirect()->back()->with('error', 'Bu oda bu otele ait değil.');
        }

        try {
            // Eğer odada aktif misafir varsa silme
            $activeStay = GuestStay::where('room_id', $room->id)
                ->where('status', 'checked_in')
                ->first();

            if ($activeStay) {
                return redirect()->back()->with('error', 'Bu odada aktif misafir var. Önce check-out yapın.');
            }

            $room->delete();
            return redirect()->back()->with('success', 'Oda başarıyla silindi.');
        } catch (\Exception $e) {
            \Log::error('Oda silme hatası: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Oda silinirken bir hata oluştu: ' . $e->getMessage());
        }
    }

    public function staff()
    {
        $user = auth()->user();
        $hotelId = $user->hotel_id;

        if (!$hotelId) {
            return view('admin.staff', [
                'role' => 'Yönetim',
                'activeTab' => 'staff',
                'staffMembers' => collect([]),
            ]);
        }

        $staffMembers = User::where('hotel_id', $hotelId)
            ->whereHas('roles', function($q) {
                $q->where('name', 'personel');
            })
            ->with('roles')
            ->orderBy('created_at', 'desc')
            ->get();

        $stats = [
            'total' => $staffMembers->count(),
        ];

        return view('admin.staff', [
            'role' => 'Yönetim',
            'activeTab' => 'staff',
            'staffMembers' => $staffMembers,
            'stats' => $stats,
        ]);
    }

    public function createStaff(Request $request)
    {
        $user = auth()->user();
        $hotelId = $user->hotel_id;

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'language' => 'nullable|string|max:10',
        ]);

        try {
            $staff = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => \Hash::make($validated['password']),
                'hotel_id' => $hotelId,
                'language' => $validated['language'] ?? 'tr',
            ]);

            // Personel rolünü ata
            $personelRole = \App\Models\Role::where('name', 'personel')->first();
            if ($personelRole) {
                $staff->roles()->attach($personelRole->id);
            }

            return redirect()->back()->with('success', 'Personel başarıyla oluşturuldu.');
        } catch (\Exception $e) {
            \Log::error('Personel oluşturma hatası: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Personel oluşturulurken bir hata oluştu: ' . $e->getMessage());
        }
    }

    public function updateStaff(Request $request, User $staff)
    {
        $user = auth()->user();
        $hotelId = $user->hotel_id;

        if ($staff->hotel_id != $hotelId) {
            return redirect()->back()->with('error', 'Bu personel bu otele ait değil.');
        }

        // Personel rolü kontrolü
        if (!$staff->hasRole('personel')) {
            return redirect()->back()->with('error', 'Bu kullanıcı personel değil.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $staff->id,
            'password' => 'nullable|string|min:8|confirmed',
            'language' => 'nullable|string|max:10',
        ]);

        try {
            $staff->name = $validated['name'];
            $staff->email = $validated['email'];
            $staff->language = $validated['language'] ?? $staff->language ?? 'tr';
            
            if (!empty($validated['password'])) {
                $staff->password = \Hash::make($validated['password']);
            }
            
            $staff->save();

            return redirect()->back()->with('success', 'Personel bilgileri başarıyla güncellendi.');
        } catch (\Exception $e) {
            \Log::error('Personel güncelleme hatası: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Personel güncellenirken bir hata oluştu: ' . $e->getMessage());
        }
    }

    public function deleteStaff(User $staff)
    {
        $user = auth()->user();
        $hotelId = $user->hotel_id;

        if ($staff->hotel_id != $hotelId) {
            return redirect()->back()->with('error', 'Bu personel bu otele ait değil.');
        }

        // Personel rolü kontrolü
        if (!$staff->hasRole('personel')) {
            return redirect()->back()->with('error', 'Bu kullanıcı personel değil.');
        }

        try {
            // Eğer personele atanmış odalar varsa kontrol et
            $assignedRooms = Room::where('assigned_staff_id', $staff->id)->count();
            if ($assignedRooms > 0) {
                return redirect()->back()->with('error', 'Bu personele atanmış odalar var. Önce oda atamalarını kaldırın.');
            }

            $staff->delete();
            return redirect()->back()->with('success', 'Personel başarıyla silindi.');
        } catch (\Exception $e) {
            \Log::error('Personel silme hatası: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Personel silinirken bir hata oluştu: ' . $e->getMessage());
        }
    }

    public function createGuest(Request $request)
    {
        $user = auth()->user();
        $hotelId = $user->hotel_id;

        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'tc_no' => 'required|string|size:11|unique:users,tc_no|regex:/^[0-9]+$/',
                'language' => 'nullable|string|max:10',
                'room_id' => 'required|exists:rooms,id',
            ]);
            // TC numarasını şifre olarak kullan
            $guest = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'tc_no' => $validated['tc_no'],
                'password' => \Hash::make($validated['tc_no']), // TC numarası şifre olarak
                'hotel_id' => $hotelId,
                'language' => $validated['language'] ?? 'tr',
            ]);

            // Misafir rolünü ata
            $guestRole = \App\Models\Role::where('name', 'misafir')->first();
            if (!$guestRole) {
                // Eğer 'misafir' rolü yoksa 'guest' rolünü dene
                $guestRole = \App\Models\Role::where('name', 'guest')->first();
            }
            
            if ($guestRole) {
                // Rol zaten atanmış mı kontrol et
                $existingRole = $guest->roles()->where('role_id', $guestRole->id)->exists();
                if (!$existingRole) {
                    $guest->roles()->attach($guestRole->id);
                }
                \Log::info('Misafir rolü atandı', [
                    'guest_id' => $guest->id,
                    'role_id' => $guestRole->id,
                    'role_name' => $guestRole->name,
                ]);
            } else {
                \Log::error('Misafir rolü bulunamadı!');
                return redirect()->back()->with('error', 'Misafir rolü bulunamadı. Lütfen sistem yöneticisine başvurun.');
            }
            
            // Misafir ve rol ilişkisini yeniden yükle
            $guest->refresh();
            $guest->load('roles');
            
            // Debug: Misafirin rollerini kontrol et
            \Log::info('Misafir oluşturuldu - Roller kontrolü', [
                'guest_id' => $guest->id,
                'guest_name' => $guest->name,
                'roles_count' => $guest->roles->count(),
                'roles' => $guest->roles->pluck('name')->toArray(),
            ]);

            // Oda ile ilişkilendir ve otomatik check-in yap
            $room = Room::findOrFail($validated['room_id']);
            
            if ($room->hotel_id != $hotelId) {
                return redirect()->back()->with('error', 'Bu oda bu otele ait değil.');
            }

            // Oda müsait mi kontrol et
            if ($room->status === 'maintenance') {
                return redirect()->back()->with('error', 'Bu oda bakımda, check-in yapılamaz.');
            }

            // Misafirin aktif bir rezervasyonu var mı kontrol et
            $existingStay = GuestStay::where('user_id', $guest->id)
                ->where('status', 'checked_in')
                ->first();

            if ($existingStay) {
                return redirect()->back()->with('error', 'Bu misafirin zaten aktif bir rezervasyonu var.');
            }

            // Oda dolu mu kontrol et
            $roomOccupied = GuestStay::where('room_id', $room->id)
                ->where('status', 'checked_in')
                ->exists();

            if ($roomOccupied && $room->status === 'available') {
                return redirect()->back()->with('error', 'Bu oda zaten dolu.');
            }

            // Check-in yap
            $guestStay = GuestStay::create([
                'hotel_id' => $hotelId,
                'user_id' => $guest->id,
                'room_id' => $room->id,
                'check_in' => now(),
                'status' => 'checked_in',
                'checked_in_by' => $user->id,
            ]);

            // Oda durumunu güncelle
            $room->status = 'occupied';
            $room->save();

            // Misafir ve rol ilişkisini yeniden yükle
            $guest->refresh();
            $guest->load('roles');
            
            // Aktif misafir kalışını manuel yükle (eager loading sorunu olabilir)
            $activeStay = GuestStay::where('user_id', $guest->id)
                ->where('status', 'checked_in')
                ->with('room.roomType', 'room.assignedStaff')
                ->first();
            
            if ($activeStay) {
                $guest->setRelation('activeGuestStay', $activeStay);
            }

            \Log::info('Misafir oluşturuldu ve check-in yapıldı', [
                'guest_id' => $guest->id,
                'guest_name' => $guest->name,
                'guest_email' => $guest->email,
                'room_id' => $room->id,
                'room_number' => $room->room_number,
                'guest_stay_id' => $guestStay->id,
                'roles' => $guest->roles->pluck('name')->toArray(),
                'has_active_stay' => $guest->activeGuestStay ? 'yes' : 'no',
            ]);

            // Hangi sayfadan geldiğine göre yönlendir
            $referer = request()->headers->get('referer');
            if ($referer && str_contains($referer, '/admin/rooms')) {
                return redirect()->route('admin.rooms')->with('success', 'Misafir başarıyla oluşturuldu ve Oda ' . $room->room_number . ' için check-in yapıldı.');
            }
            
            return redirect()->route('admin.guests')->with('success', 'Misafir başarıyla oluşturuldu ve Oda ' . $room->room_number . ' için check-in yapıldı.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Misafir oluşturma validation hatası', ['errors' => $e->errors()]);
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            \Log::error('Misafir oluşturma hatası: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'Misafir oluşturulurken bir hata oluştu: ' . $e->getMessage())->withInput();
        }
    }

    public function getRoomHistory(Room $room)
    {
        $user = auth()->user();
        $hotelId = $user->hotel_id;

        if ($room->hotel_id != $hotelId) {
            return response()->json(['success' => false, 'message' => 'Bu oda bu otele ait değil.'], 403);
        }

        $guestStays = GuestStay::where('room_id', $room->id)
            ->with('user')
            ->orderBy('check_in', 'desc')
            ->get()
            ->map(function($stay) {
                return [
                    'id' => $stay->id,
                    'user_name' => $stay->user->name ?? 'N/A',
                    'user_email' => $stay->user->email ?? '',
                    'check_in' => \Carbon\Carbon::parse($stay->check_in)->format('d.m.Y H:i'),
                    'check_out' => $stay->check_out ? \Carbon\Carbon::parse($stay->check_out)->format('d.m.Y H:i') : null,
                    'status' => $stay->status,
                ];
            });

        return response()->json(['success' => true, 'guestStays' => $guestStays]);
    }

    public function assignStaffToRoom(Request $request)
    {
        $user = auth()->user();
        $hotelId = $user->hotel_id;

        $validated = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'staff_id' => 'nullable|exists:users,id',
        ]);

        try {
            $room = Room::findOrFail($validated['room_id']);
            if ($room->hotel_id != $hotelId) {
                return redirect()->back()->with('error', 'Bu oda bu otele ait değil.');
            }

            // Personel ataması yapılıyorsa, personelin bu otele ait olduğunu kontrol et
            if ($validated['staff_id']) {
                $staff = User::findOrFail($validated['staff_id']);
                if ($staff->hotel_id != $hotelId || !$staff->hasRole('personel')) {
                    return redirect()->back()->with('error', 'Geçersiz personel seçimi.');
                }
            }

            $room->assigned_staff_id = $validated['staff_id'] ?? null;
            $room->save();

            return redirect()->back()->with('success', 'Personel ataması başarıyla yapıldı.');
        } catch (\Exception $e) {
            \Log::error('Personel atama hatası: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Personel ataması yapılırken bir hata oluştu: ' . $e->getMessage());
        }
    }
    
    public function billing()
    {
        $user = auth()->user();
        $hotelId = $user->hotel_id;

        $stats = [
            'today_revenue' => 0,
            'month_revenue' => 0,
            'year_revenue' => 0,
        ];

        return view('admin.billing', [
            'role' => 'Yönetim',
            'activeTab' => 'billing',
            'stats' => $stats,
        ]);
    }
    
    public function analytics()
    {
        $user = auth()->user();
        $hotelId = $user->hotel_id;

        if (!$hotelId) {
            return view('admin.analytics', [
                'role' => 'Yönetim',
                'activeTab' => 'analytics',
                'roomOccupancy' => ['available' => 0, 'occupied' => 0, 'maintenance' => 0],
                'taskStatus' => ['pending' => 0, 'in_progress' => 0, 'completed' => 0],
            ]);
        }

        // Oda doluluk oranı
        $roomOccupancy = [
            'available' => Room::where('hotel_id', $hotelId)->where('status', 'available')->count(),
            'occupied' => Room::where('hotel_id', $hotelId)->where('status', 'occupied')->count(),
            'maintenance' => Room::where('hotel_id', $hotelId)->where('status', 'maintenance')->count(),
        ];

        // Görev durumu
        $taskStatus = [
            'pending' => Task::where('hotel_id', $hotelId)->where('status', 'pending')->count(),
            'in_progress' => Task::where('hotel_id', $hotelId)->where('status', 'in_progress')->count(),
            'completed' => Task::where('hotel_id', $hotelId)->where('status', 'completed')->count(),
        ];

        return view('admin.analytics', [
            'role' => 'Yönetim',
            'activeTab' => 'analytics',
            'roomOccupancy' => $roomOccupancy,
            'taskStatus' => $taskStatus,
        ]);
    }
    
    public function settings()
    {
        $user = auth()->user();
        $hotel = $user->hotel;

        return view('admin.settings', [
            'role' => 'Yönetim',
            'activeTab' => 'settings',
            'hotel' => $hotel,
        ]);
    }

    public function updateSettings(Request $request)
    {
        $user = auth()->user();
        $hotel = $user->hotel;

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string',
        ]);

        try {
            if ($hotel) {
                $hotel->update($validated);
            } else {
                $hotel = \App\Models\Hotel::create(array_merge($validated, ['id' => $user->hotel_id ?? null]));
            }

            return redirect()->route('admin.settings')->with('success', 'Ayarlar başarıyla güncellendi.');
        } catch (\Exception $e) {
            return redirect()->route('admin.settings')->with('error', 'Ayarlar güncellenirken bir hata oluştu.');
        }
    }

    public function storeTask(Request $request)
    {
        $user = auth()->user();
        $hotelId = $user->hotel_id;

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high,urgent',
            'assigned_to' => 'nullable|exists:users,id',
            'due_date' => 'nullable|date',
        ]);

        try {
            Task::create([
                'hotel_id' => $hotelId,
                'created_by' => $user->id,
                'assigned_to' => $validated['assigned_to'] ?? null,
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'priority' => $validated['priority'],
                'due_date' => $validated['due_date'] ?? null,
                'status' => 'pending',
            ]);

            return redirect()->route('admin.tasks')->with('success', 'Görev başarıyla oluşturuldu.');
        } catch (\Exception $e) {
            return redirect()->route('admin.tasks')->with('error', 'Görev oluşturulurken bir hata oluştu.');
        }
    }

    public function updateTask(Request $request, Task $task)
    {
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'sometimes|in:low,medium,high,urgent',
            'status' => 'sometimes|in:pending,in_progress,completed,cancelled',
            'assigned_to' => 'nullable|exists:users,id',
            'due_date' => 'nullable|date',
        ]);

        try {
            $task->update($validated);
            if ($validated['status'] ?? null === 'completed') {
                $task->update(['completed_at' => now()]);
            }

            return redirect()->route('admin.tasks')->with('success', 'Görev başarıyla güncellendi.');
        } catch (\Exception $e) {
            return redirect()->route('admin.tasks')->with('error', 'Görev güncellenirken bir hata oluştu.');
        }
    }

    public function deleteTask(Task $task)
    {
        try {
            $task->delete();
            return redirect()->route('admin.tasks')->with('success', 'Görev başarıyla silindi.');
        } catch (\Exception $e) {
            return redirect()->route('admin.tasks')->with('error', 'Görev silinirken bir hata oluştu.');
        }
    }

    public function storeMessage(Request $request)
    {
        $user = auth()->user();
        $hotelId = $user->hotel_id;

        $validated = $request->validate([
            'to_user_id' => 'required|exists:users,id',
            'subject' => 'nullable|string|max:255',
            'content' => 'required|string',
            'type' => 'nullable|in:internal,guest,system',
            'priority' => 'nullable|in:low,medium,high',
        ]);

        try {
            // Kullanıcının dil ayarını al (önce tarayıcı dilini kontrol et, sonra kullanıcının language field'ı)
            $browserLanguage = $request->input('browser_language');
            $userLanguage = $user->language ?? $browserLanguage ?? 'tr';
            
            // Desteklenen dilleri kontrol et
            $supportedLanguages = ['tr', 'en', 'de', 'fr', 'es', 'it', 'ru', 'ar', 'zh', 'ja'];
            if (!in_array($userLanguage, $supportedLanguages)) {
                $userLanguage = 'tr';
            }
            
            // Eğer kullanıcının language field'ı boşsa ve tarayıcı dili varsa, güncelle
            if (empty($user->language) && $browserLanguage && in_array($browserLanguage, $supportedLanguages)) {
                $user->language = $browserLanguage;
                $user->save();
                $userLanguage = $browserLanguage;
            }
            
            $toUser = \App\Models\User::find($validated['to_user_id']);
            
            if (!$toUser) {
                return redirect()->route('admin.messages')->with('error', 'Alıcı kullanıcı bulunamadı.');
            }
            
            $toUserLanguage = $toUser->language ?? 'tr';
            
            // Mesajın dilini tespit et
            $detectedLanguage = \App\Services\TranslationService::detectLanguage($validated['content']);
            
            // Alıcının diline çevir
            $translatedContent = $toUserLanguage !== $detectedLanguage 
                ? \App\Services\TranslationService::translate($validated['content'], $toUserLanguage, $detectedLanguage)
                : $validated['content'];

            // Alıcı misafir ise type'ı guest yap
            $isGuest = $toUser->hasRole('misafir') || $toUser->hasRole('guest');
            $messageType = $validated['type'] ?? ($isGuest ? 'guest' : 'internal');
            if (!in_array($messageType, ['internal', 'guest', 'system'])) {
                $messageType = $isGuest ? 'guest' : 'internal';
            }

            $message = Message::create([
                'hotel_id' => $hotelId,
                'sender_id' => $user->id, // sender_id direkt kullan
                'from_user_id' => $user->id, // from_user_id de set et (model map ediyor)
                'to_user_id' => $validated['to_user_id'],
                'subject' => $validated['subject'] ?? null,
                'content' => $translatedContent,
                'original_content' => $validated['content'],
                'original_language' => $detectedLanguage,
                'translated_content' => $translatedContent,
                'type' => $messageType,
                'priority' => $validated['priority'] ?? 'medium',
                'is_read' => false,
            ]);
            
            \Log::info('Admin message sent', [
                'message_id' => $message->id,
                'from_user_id' => $user->id,
                'to_user_id' => $validated['to_user_id'],
                'to_user_is_guest' => $isGuest,
                'type' => $messageType,
                'sender_id' => $message->sender_id ?? $message->attributes['sender_id'] ?? null,
                'content' => substr($translatedContent, 0, 50),
            ]);

            return redirect()->route('admin.messages', ['to_user_id' => $validated['to_user_id']])->with('success', 'Mesaj başarıyla gönderildi.');
        } catch (\Exception $e) {
            \Log::error('Mesaj gönderme hatası (Admin): ' . $e->getMessage(), [
                'exception' => $e,
                'user_id' => auth()->id(),
                'request_data' => $request->all()
            ]);
            return redirect()->route('admin.messages')->with('error', 'Mesaj gönderilirken bir hata oluştu: ' . $e->getMessage());
        }
    }

    public function markMessageRead(Message $message)
    {
        try {
            $message->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false], 500);
        }
    }

    public function notifications()
    {
        $user = auth()->user();
        $hotelId = $user->hotel_id;

        if (!$hotelId) {
            return view('admin.notifications', [
                'role' => 'Yönetim',
                'activeTab' => 'notifications',
                'notifications' => collect([]),
            ]);
        }

        // Mesajlar (okunmamış)
        $unreadMessages = Message::where('hotel_id', $hotelId)
            ->where(function($q) {
                $q->whereNull('is_read')->orWhere('is_read', false);
            })
            ->with(['fromUser', 'toUser'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($message) {
                return [
                    'type' => 'message',
                    'title' => $message->fromUser->name ?? 'Bilinmeyen',
                    'description' => Str::limit($message->content, 100),
                    'time' => $message->created_at,
                    'icon' => 'message-square',
                    'color' => 'blue',
                    'data' => $message,
                ];
            });

        // Yeni görevler (pending)
        $newTasks = Task::where('hotel_id', $hotelId)
            ->where('status', 'pending')
            ->with(['assignedTo', 'createdBy'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($task) {
                return [
                    'type' => 'task',
                    'title' => 'Yeni Görev: ' . $task->title,
                    'description' => Str::limit($task->description ?? 'Açıklama yok', 100),
                    'time' => $task->created_at,
                    'icon' => 'clipboard-list',
                    'color' => 'orange',
                    'data' => $task,
                ];
            });

        // Yeni talepler (pending)
        $newRequests = \App\Models\Request::where('hotel_id', $hotelId)
            ->where('status', 'pending')
            ->with(['user'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($request) {
                return [
                    'type' => 'request',
                    'title' => 'Yeni Talep: ' . ucfirst($request->type ?? 'genel'),
                    'description' => Str::limit($request->description ?? 'Açıklama yok', 100),
                    'time' => $request->created_at,
                    'icon' => 'concierge-bell',
                    'color' => 'purple',
                    'data' => $request,
                ];
            });

        // Yeni arızalar (open)
        $newTickets = Ticket::where('hotel_id', $hotelId)
            ->where('status', 'open')
            ->with(['createdBy', 'assignedTo'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($ticket) {
                return [
                    'type' => 'ticket',
                    'title' => 'Yeni Arıza: ' . $ticket->title,
                    'description' => Str::limit($ticket->description ?? 'Açıklama yok', 100),
                    'time' => $ticket->created_at,
                    'icon' => 'wrench',
                    'color' => 'red',
                    'data' => $ticket,
                ];
            });

        // Tüm bildirimleri birleştir ve tarihe göre sırala
        $notifications = collect()
            ->merge($unreadMessages)
            ->merge($newTasks)
            ->merge($newRequests)
            ->merge($newTickets)
            ->sortByDesc('time')
            ->values();

        return view('admin.notifications', [
            'role' => 'Yönetim',
            'activeTab' => 'notifications',
            'notifications' => $notifications,
        ]);
    }

    public function events()
    {
        $user = auth()->user();
        $hotelId = $user->hotel_id;

        // hotel_id yoksa otomatik olarak bir hotel oluştur
        if (!$hotelId) {
            try {
                $hotel = \App\Models\Hotel::create([
                    'name' => $user->name . ' Oteli',
                    'email' => $user->email,
                    'address' => 'Adres bilgisi eklenmedi',
                    'phone' => 'Telefon bilgisi eklenmedi',
                ]);
                $user->hotel_id = $hotel->id;
                $user->save();
                $hotelId = $hotel->id;
            } catch (\Exception $e) {
                \Log::error('Hotel oluşturma hatası: ' . $e->getMessage(), [
                    'exception' => $e,
                    'user_id' => $user->id,
                ]);
                return view('admin.events', [
                    'role' => 'Yönetim',
                    'activeTab' => 'events',
                    'events' => collect([]),
                ])->with('error', 'Otel bilgisi bulunamadı ve otomatik oluşturulamadı. Lütfen ayarlardan otel bilgilerinizi kontrol edin.');
            }
        }

        $events = Event::where('hotel_id', $hotelId)
            ->orderBy('priority', 'desc')
            ->orderBy('start_date', 'asc')
            ->paginate(15);

        return view('admin.events', [
            'role' => 'Yönetim',
            'activeTab' => 'events',
            'events' => $events,
        ]);
    }

    public function storeEvent(Request $request)
    {
        $user = auth()->user();
        $hotelId = $user->hotel_id;

        // hotel_id yoksa otomatik olarak bir hotel oluştur
        if (!$hotelId) {
            try {
                $hotel = \App\Models\Hotel::create([
                    'name' => $user->name . ' Oteli',
                    'email' => $user->email,
                    'address' => 'Adres bilgisi eklenmedi',
                    'phone' => 'Telefon bilgisi eklenmedi',
                ]);
                $user->hotel_id = $hotel->id;
                $user->save();
                $hotelId = $hotel->id;
            } catch (\Exception $e) {
                \Log::error('Hotel oluşturma hatası: ' . $e->getMessage(), [
                    'exception' => $e,
                    'user_id' => $user->id,
                ]);
                return redirect()->route('admin.events')->with('error', 'Otel bilgisi bulunamadı ve otomatik oluşturulamadı. Lütfen ayarlardan otel bilgilerinizi kontrol edin.');
            }
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'location' => 'nullable|string|max:255',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'is_active' => 'nullable|boolean',
            'priority' => 'nullable|integer|min:0|max:100',
        ]);

        try {
            $event = Event::create([
                'hotel_id' => $hotelId,
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'] ?? null,
                'location' => $validated['location'] ?? null,
                'image_path' => null, // Artık event_images tablosunda saklanacak
                'is_active' => isset($validated['is_active']) ? (bool)$validated['is_active'] : true,
                'priority' => $validated['priority'] ?? 0,
            ]);

            // Görselleri yükle
            if ($request->hasFile('images')) {
                $order = 0;
                foreach ($request->file('images') as $image) {
                    $path = $image->store('events', 'public');
                    \App\Models\EventImage::create([
                        'event_id' => $event->id,
                        'image_path' => 'storage/' . $path,
                        'order' => $order++,
                    ]);
                }
            }

            return redirect()->route('admin.events')->with('success', 'Etkinlik başarıyla oluşturuldu.');
        } catch (\Exception $e) {
            \Log::error('Etkinlik oluşturma hatası: ' . $e->getMessage(), [
                'exception' => $e,
                'user_id' => auth()->id(),
                'hotel_id' => $hotelId,
                'request_data' => $request->all()
            ]);
            return redirect()->route('admin.events')->with('error', 'Etkinlik oluşturulurken bir hata oluştu: ' . $e->getMessage());
        }
    }

    public function updateEvent(Request $request, Event $event)
    {
        $user = auth()->user();
        $hotelId = $user->hotel_id;

        // Etkinliğin bu otelin etkinliği olduğunu kontrol et
        if ($event->hotel_id !== $hotelId) {
            abort(404);
        }

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'sometimes|date',
            'end_date' => 'nullable|date|after:start_date',
            'location' => 'nullable|string|max:255',
            'image_path' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
            'priority' => 'nullable|integer|min:0|max:100',
        ]);

        try {
            $updateData = $validated;
            if (isset($updateData['is_active'])) {
                $updateData['is_active'] = (bool)$updateData['is_active'];
            }
            $event->update($updateData);
            return redirect()->route('admin.events')->with('success', 'Etkinlik başarıyla güncellendi.');
        } catch (\Exception $e) {
            \Log::error('Etkinlik güncelleme hatası: ' . $e->getMessage(), [
                'exception' => $e,
                'event_id' => $event->id,
                'request_data' => $request->all()
            ]);
            return redirect()->route('admin.events')->with('error', 'Etkinlik güncellenirken bir hata oluştu: ' . $e->getMessage());
        }
    }

    public function deleteEvent(Event $event)
    {
        $user = auth()->user();
        $hotelId = $user->hotel_id;

        // Etkinliğin bu otelin etkinliği olduğunu kontrol et
        if ($event->hotel_id !== $hotelId) {
            abort(404);
        }

        try {
            $event->delete();
            return redirect()->route('admin.events')->with('success', 'Etkinlik başarıyla silindi.');
        } catch (\Exception $e) {
            return redirect()->route('admin.events')->with('error', 'Etkinlik silinirken bir hata oluştu.');
        }
    }

    public function feedbacks()
    {
        $user = auth()->user();
        $hotelId = $user->hotel_id;

        if (!$hotelId) {
            return view('admin.feedbacks', [
                'role' => 'Yönetim',
                'activeTab' => 'feedbacks',
                'feedbacks' => collect([]),
                'stats' => [
                    'total' => 0,
                    'average_rating' => 0,
                    'unresponded' => 0,
                ],
            ]);
        }

        $feedbacks = Feedback::where('hotel_id', $hotelId)
            ->with(['user'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $stats = [
            'total' => Feedback::where('hotel_id', $hotelId)->count(),
            'average_rating' => Feedback::where('hotel_id', $hotelId)->avg('rating') ?? 0,
            'unresponded' => Feedback::where('hotel_id', $hotelId)->where('is_responded', false)->count(),
        ];

        return view('admin.feedbacks', [
            'role' => 'Yönetim',
            'activeTab' => 'feedbacks',
            'feedbacks' => $feedbacks,
            'stats' => $stats,
        ]);
    }

    public function updateFeedback(Request $request, Feedback $feedback)
    {
        $user = auth()->user();
        $hotelId = $user->hotel_id;

        if ($feedback->hotel_id !== $hotelId) {
            return redirect()->route('admin.feedbacks')->with('error', 'Bu geri bildirimi güncelleyemezsiniz.');
        }

        $validated = $request->validate([
            'is_responded' => 'nullable|boolean',
            'is_public' => 'nullable|boolean',
        ]);

        try {
            if (isset($validated['is_responded'])) {
                $feedback->is_responded = $validated['is_responded'];
            }
            if (isset($validated['is_public'])) {
                $feedback->is_public = $validated['is_public'];
            }
            $feedback->save();

            if ($request->expectsJson()) {
                return response()->json(['success' => true]);
            }
            return redirect()->route('admin.feedbacks')->with('success', 'Geri bildirim güncellendi.');
        } catch (\Exception $e) {
            \Log::error('Geri bildirim güncelleme hatası: ' . $e->getMessage());
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
            }
            return redirect()->route('admin.feedbacks')->with('error', 'Geri bildirim güncellenirken bir hata oluştu.');
        }
    }
}
