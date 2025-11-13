<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Message;
use App\Models\Ticket;
use App\Models\Schedule;
use App\Models\Resource;
use App\Models\Event;
use App\Models\GuestStay;
use App\Models\User;
use App\Models\Room;
use Illuminate\Http\Request;
use Carbon\Carbon;

class StaffController extends Controller
{
    public function tasks()
    {
        $user = auth()->user();
        $hotelId = $user->hotel_id;

        if (!$hotelId) {
            return view('staff.tasks', [
                'role' => 'Personel',
                'activeTab' => 'mytasks',
                'tasks' => collect([]),
                'stats' => ['total' => 0, 'pending' => 0, 'in_progress' => 0, 'completed' => 0],
            ]);
        }

        $tasks = Task::where('hotel_id', $hotelId)
            ->where('assigned_to', $user->id)
            ->with(['createdBy', 'assignedTo'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $stats = [
            'total' => Task::where('hotel_id', $hotelId)->where('assigned_to', $user->id)->count(),
            'pending' => Task::where('hotel_id', $hotelId)->where('assigned_to', $user->id)->where('status', 'pending')->count(),
            'in_progress' => Task::where('hotel_id', $hotelId)->where('assigned_to', $user->id)->where('status', 'in_progress')->count(),
            'completed' => Task::where('hotel_id', $hotelId)->where('assigned_to', $user->id)->where('status', 'completed')->count(),
        ];

        return view('staff.tasks', [
            'role' => 'Personel',
            'activeTab' => 'mytasks',
            'tasks' => $tasks,
            'stats' => $stats,
        ]);
    }
    
    public function schedule()
    {
        $user = auth()->user();
        $hotelId = $user->hotel_id;

        $schedules = Schedule::where('hotel_id', $hotelId)
            ->where('user_id', $user->id)
            ->where('date', '>=', today())
            ->orderBy('date', 'asc')
            ->orderBy('start_time', 'asc')
            ->paginate(15);

        $upcomingSchedules = Schedule::where('hotel_id', $hotelId)
            ->where('user_id', $user->id)
            ->where('date', '>=', today())
            ->orderBy('date', 'asc')
            ->orderBy('start_time', 'asc')
            ->take(5)
            ->get();

        return view('staff.schedule', [
            'role' => 'Personel',
            'activeTab' => 'schedule',
            'schedules' => $schedules,
            'upcomingSchedules' => $upcomingSchedules,
        ]);
    }
    
    public function tickets()
    {
        $user = auth()->user();
        $hotelId = $user->hotel_id;

        $tickets = Ticket::where('hotel_id', $hotelId)
            ->where(function($q) use ($user) {
                $q->where('created_by', $user->id)
                  ->orWhere('assigned_to', $user->id);
            })
            ->with(['createdBy', 'assignedTo'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $stats = [
            'total' => Ticket::where('hotel_id', $hotelId)
                ->where(function($q) use ($user) {
                    $q->where('created_by', $user->id)
                      ->orWhere('assigned_to', $user->id);
                })
                ->count(),
            'open' => Ticket::where('hotel_id', $hotelId)
                ->where(function($q) use ($user) {
                    $q->where('created_by', $user->id)
                      ->orWhere('assigned_to', $user->id);
                })
                ->where('status', 'open')
                ->count(),
            'in_progress' => Ticket::where('hotel_id', $hotelId)
                ->where(function($q) use ($user) {
                    $q->where('created_by', $user->id)
                      ->orWhere('assigned_to', $user->id);
                })
                ->where('status', 'in_progress')
                ->count(),
            'resolved' => Ticket::where('hotel_id', $hotelId)
                ->where(function($q) use ($user) {
                    $q->where('created_by', $user->id)
                      ->orWhere('assigned_to', $user->id);
                })
                ->where('status', 'resolved')
                ->count(),
        ];

        return view('staff.tickets', [
            'role' => 'Personel',
            'activeTab' => 'tickets',
            'tickets' => $tickets,
            'stats' => $stats,
        ]);
    }
    
    public function inbox(Request $request)
    {
        $user = auth()->user();
        $hotelId = $user->hotel_id;
        $userLanguage = $user->language ?? 'tr';

        if (!$hotelId) {
            return view('staff.inbox', [
                'role' => 'Personel',
                'activeTab' => 'inbox',
                'messages' => collect([]),
                'unreadCount' => 0,
            ]);
        }

        // Belirli bir kullanıcıyla chat thread'i için to_user_id parametresi
        $toUserId = $request->get('to_user_id');
        
        if ($toUserId) {
            // Belirli bir kullanıcıyla olan mesajları getir
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
        $messages = $messages->transform(function($message) use ($userLanguage, $user) {
            // sender_id'yi kontrol ediyoruz çünkü veritabanında bu kolon var
            $senderId = $message->attributes['sender_id'] ?? $message->from_user_id;
            if ($senderId == $user->id) {
                $message->display_content = $message->original_content ?? $message->content;
            } else {
                // Eğer mesaj kullanıcıya geliyorsa, kullanıcının diline çevrilmiş içeriği göster
                if ($message->translated_content && $message->original_language !== $userLanguage) {
                    // Eğer çevrilmiş içerik varsa ve dil farklıysa, tekrar çevir
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

        $unreadCount = Message::where('hotel_id', $hotelId)
            ->where('to_user_id', $user->id)
            ->where(function($q) {
                $q->whereNull('is_read')->orWhere('is_read', false);
            })
            ->count();

        // Chat thread için kullanıcı bilgisi
        $chatUser = $toUserId ? \App\Models\User::find($toUserId) : null;

        // Mesajlaşma geçmişi olan kullanıcıları getir (kullanıcı seçimi için)
        $chatUsers = collect();
        if (!$toUserId) {
            $chatUsers = Message::where('hotel_id', $hotelId)
                ->where(function($query) use ($user) {
                    $query->where('sender_id', $user->id)
                          ->orWhere('to_user_id', $user->id);
                })
                ->with(['fromUser', 'toUser'])
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function($message) use ($user) {
                    $senderId = $message->attributes['sender_id'] ?? $message->from_user_id;
                    if ($senderId == $user->id) {
                        return $message->toUser;
                    } else {
                        return $message->fromUser;
                    }
                })
                ->filter()
                ->unique('id')
                ->take(10);
        }

        return view('staff.inbox', [
            'role' => 'Personel',
            'activeTab' => 'inbox',
            'messages' => $messages,
            'unreadCount' => $unreadCount,
            'chatUser' => $chatUser,
            'toUserId' => $toUserId,
            'chatUsers' => $chatUsers,
        ]);
    }
    
    public function resources()
    {
        $user = auth()->user();
        $hotelId = $user->hotel_id;

        $resources = Resource::where('hotel_id', $hotelId)
            ->orderBy('name', 'asc')
            ->paginate(15);

        $stats = [
            'total' => Resource::where('hotel_id', $hotelId)->count(),
            'available' => Resource::where('hotel_id', $hotelId)->where('status', 'available')->count(),
            'low_stock' => Resource::where('hotel_id', $hotelId)->where('status', 'low_stock')->count(),
            'out_of_stock' => Resource::where('hotel_id', $hotelId)->where('status', 'out_of_stock')->count(),
        ];

        return view('staff.resources', [
            'role' => 'Personel',
            'activeTab' => 'resources',
            'resources' => $resources,
            'stats' => $stats,
        ]);
    }

    public function updateTaskStatus(Request $request, Task $task)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,in_progress,completed,cancelled',
        ]);

        try {
            $task->update($validated);
            if ($validated['status'] === 'completed') {
                $task->update(['completed_at' => now()]);
            }

            return redirect()->route('staff.tasks')->with('success', 'Görev durumu güncellendi.');
        } catch (\Exception $e) {
            return redirect()->route('staff.tasks')->with('error', 'Görev durumu güncellenirken bir hata oluştu.');
        }
    }

    public function updateTicketStatus(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'status' => 'required|in:open,in_progress,resolved,closed',
        ]);

        try {
            $ticket->update($validated);
            return redirect()->route('staff.tickets')->with('success', 'Arıza durumu güncellendi.');
        } catch (\Exception $e) {
            return redirect()->route('staff.tickets')->with('error', 'Arıza durumu güncellenirken bir hata oluştu.');
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
                return redirect()->route('staff.inbox')->with('error', 'Alıcı kullanıcı bulunamadı.');
            }
            
            $toUserLanguage = $toUser->language ?? 'tr';
            
            // Mesajın dilini tespit et
            $detectedLanguage = \App\Services\TranslationService::detectLanguage($validated['content']);
            
            // Alıcının diline çevir
            $translatedContent = $toUserLanguage !== $detectedLanguage 
                ? \App\Services\TranslationService::translate($validated['content'], $toUserLanguage, $detectedLanguage)
                : $validated['content'];

            // type değerini kontrol et ve geçerli değerlerden biri olduğundan emin ol
            $messageType = $validated['type'] ?? 'internal';
            if (!in_array($messageType, ['internal', 'guest', 'system'])) {
                $messageType = 'internal';
            }

            Message::create([
                'hotel_id' => $hotelId,
                'from_user_id' => $user->id,
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

            return redirect()->route('staff.inbox', ['to_user_id' => $validated['to_user_id']])->with('success', 'Mesaj başarıyla gönderildi.');
        } catch (\Exception $e) {
            \Log::error('Mesaj gönderme hatası (Staff): ' . $e->getMessage(), [
                'exception' => $e,
                'user_id' => auth()->id(),
                'request_data' => $request->all()
            ]);
            return redirect()->route('staff.inbox')->with('error', 'Mesaj gönderilirken bir hata oluştu: ' . $e->getMessage());
        }
    }

    public function events()
    {
        $user = auth()->user();
        $hotelId = $user->hotel_id;

        $events = $hotelId ? Event::where('hotel_id', $hotelId)
            ->orderBy('priority', 'desc')
            ->orderBy('start_date', 'asc')
            ->paginate(15) : collect([]);

        return view('staff.events', [
            'role' => 'Personel',
            'activeTab' => 'events',
            'events' => $events,
        ]);
    }

    public function storeEvent(Request $request)
    {
        $user = auth()->user();
        $hotelId = $user->hotel_id;

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'location' => 'nullable|string|max:255',
            'image_path' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'priority' => 'nullable|integer|min:0|max:100',
        ]);

        try {
            Event::create([
                'hotel_id' => $hotelId,
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'] ?? null,
                'location' => $validated['location'] ?? null,
                'image_path' => $validated['image_path'] ?? null,
                'is_active' => $validated['is_active'] ?? true,
                'priority' => $validated['priority'] ?? 0,
            ]);

            return redirect()->route('staff.events')->with('success', 'Etkinlik başarıyla oluşturuldu.');
        } catch (\Exception $e) {
            return redirect()->route('staff.events')->with('error', 'Etkinlik oluşturulurken bir hata oluştu.');
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
            'is_active' => 'boolean',
            'priority' => 'nullable|integer|min:0|max:100',
        ]);

        try {
            $event->update($validated);
            return redirect()->route('staff.events')->with('success', 'Etkinlik başarıyla güncellendi.');
        } catch (\Exception $e) {
            return redirect()->route('staff.events')->with('error', 'Etkinlik güncellenirken bir hata oluştu.');
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
            return redirect()->route('staff.events')->with('success', 'Etkinlik başarıyla silindi.');
        } catch (\Exception $e) {
            return redirect()->route('staff.events')->with('error', 'Etkinlik silinirken bir hata oluştu.');
        }
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
}
