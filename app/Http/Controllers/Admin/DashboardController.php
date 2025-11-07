<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Message;
use App\Models\Reservation;
use App\Models\User;
use App\Models\Room;
use App\Models\Ticket;
use App\Models\Feedback;
use App\Models\Request as GuestRequest;
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
                'recentReservations' => collect([]),
                'recentMessages' => collect([]),
                'todayRevenue' => 0,
                'monthRevenue' => 0,
                'monthlyReservations' => [],
            ]);
        }

        // İstatistikler
        $stats = [
            'total_tasks' => Task::where('hotel_id', $hotelId)->count(),
            'pending_tasks' => Task::where('hotel_id', $hotelId)->where('status', 'pending')->count(),
            'completed_tasks' => Task::where('hotel_id', $hotelId)->where('status', 'completed')->count(),
            'total_reservations' => Reservation::where('hotel_id', $hotelId)->count(),
            'pending_reservations' => Reservation::where('hotel_id', $hotelId)->where('status', 'pending')->count(),
            'confirmed_reservations' => Reservation::where('hotel_id', $hotelId)->where('status', 'confirmed')->count(),
            'total_messages' => Message::where('hotel_id', $hotelId)->count(),
            'unread_messages' => Message::where('hotel_id', $hotelId)->where(function($q) {
                $q->whereNull('is_read')->orWhere('is_read', false);
            })->count(),
            'total_guests' => User::where('hotel_id', $hotelId)->whereHas('roles', function($q) {
                $q->where('name', 'misafir');
            })->count(),
            'total_rooms' => Room::where('hotel_id', $hotelId)->count(),
            'available_rooms' => Room::where('hotel_id', $hotelId)->where('status', 'available')->count(),
            'occupied_rooms' => Room::where('hotel_id', $hotelId)->where('status', 'occupied')->count(),
        ];

        // Son görevler
        $recentTasks = Task::where('hotel_id', $hotelId)
            ->with(['assignedTo', 'createdBy'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Son rezervasyonlar
        $recentReservations = Reservation::where('hotel_id', $hotelId)
            ->with(['user', 'room.roomType'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Son mesajlar
        $recentMessages = Message::where('hotel_id', $hotelId)
            ->with(['fromUser', 'toUser'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Bugünkü gelir
        $todayRevenue = Reservation::where('hotel_id', $hotelId)
            ->whereDate('created_at', today())
            ->where('status', 'confirmed')
            ->sum('total_price');

        // Bu ay gelir
        $monthRevenue = Reservation::where('hotel_id', $hotelId)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->where('status', 'confirmed')
            ->sum('total_price');

        // Aylık rezervasyon grafiği için veri
        $monthlyReservations = Reservation::where('hotel_id', $hotelId)
            ->whereYear('created_at', now()->year)
            ->selectRaw('EXTRACT(MONTH FROM created_at) as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('count', 'month')
            ->toArray();

        return view('admin.dashboard', [
            'role' => 'Yönetim',
            'activeTab' => 'dashboard',
            'stats' => $stats,
            'recentTasks' => $recentTasks,
            'recentReservations' => $recentReservations,
            'recentMessages' => $recentMessages,
            'todayRevenue' => $todayRevenue,
            'monthRevenue' => $monthRevenue,
            'monthlyReservations' => $monthlyReservations,
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
    
    public function messages()
    {
        $user = auth()->user();
        $hotelId = $user->hotel_id;

        $messages = Message::where('hotel_id', $hotelId)
            ->with(['fromUser', 'toUser'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $stats = [
            'total' => Message::where('hotel_id', $hotelId)->count(),
            'unread' => Message::where('hotel_id', $hotelId)->where(function($q) {
                $q->whereNull('is_read')->orWhere('is_read', false);
            })->count(),
            'internal' => Message::where('hotel_id', $hotelId)->where('type', 'internal')->count(),
            'guest' => Message::where('hotel_id', $hotelId)->where('type', 'guest')->count(),
        ];

        return view('admin.messages', [
            'role' => 'Yönetim',
            'activeTab' => 'messages',
            'messages' => $messages,
            'stats' => $stats,
        ]);
    }
    
    public function guests()
    {
        $user = auth()->user();
        $hotelId = $user->hotel_id;

        $guests = User::where('hotel_id', $hotelId)
            ->whereHas('roles', function($q) {
                $q->where('name', 'misafir');
            })
            ->with(['roles', 'reservations'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $stats = [
            'total' => User::where('hotel_id', $hotelId)->whereHas('roles', function($q) {
                $q->where('name', 'misafir');
            })->count(),
            'active' => User::where('hotel_id', $hotelId)
                ->whereHas('roles', function($q) {
                    $q->where('name', 'misafir');
                })
                ->whereHas('reservations', function($q) {
                    $q->where('status', 'confirmed')
                      ->where('check_out_date', '>=', today());
                })
                ->count(),
        ];

        return view('admin.guests', [
            'role' => 'Yönetim',
            'activeTab' => 'guests',
            'guests' => $guests,
            'stats' => $stats,
        ]);
    }
    
    public function reservations()
    {
        $user = auth()->user();
        $hotelId = $user->hotel_id;

        $reservations = Reservation::where('hotel_id', $hotelId)
            ->with(['user', 'room.roomType'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $stats = [
            'total' => Reservation::where('hotel_id', $hotelId)->count(),
            'pending' => Reservation::where('hotel_id', $hotelId)->where('status', 'pending')->count(),
            'confirmed' => Reservation::where('hotel_id', $hotelId)->where('status', 'confirmed')->count(),
            'cancelled' => Reservation::where('hotel_id', $hotelId)->where('status', 'cancelled')->count(),
        ];

        return view('admin.reservations', [
            'role' => 'Yönetim',
            'activeTab' => 'reservations',
            'reservations' => $reservations,
            'stats' => $stats,
        ]);
    }
    
    public function billing()
    {
        $user = auth()->user();
        $hotelId = $user->hotel_id;

        $reservations = Reservation::where('hotel_id', $hotelId)
            ->where('status', 'confirmed')
            ->with(['user', 'room.roomType'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $stats = [
            'today_revenue' => Reservation::where('hotel_id', $hotelId)
                ->whereDate('created_at', today())
                ->where('status', 'confirmed')
                ->sum('total_price'),
            'month_revenue' => Reservation::where('hotel_id', $hotelId)
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->where('status', 'confirmed')
                ->sum('total_price'),
            'year_revenue' => Reservation::where('hotel_id', $hotelId)
                ->whereYear('created_at', now()->year)
                ->where('status', 'confirmed')
                ->sum('total_price'),
        ];

        return view('admin.billing', [
            'role' => 'Yönetim',
            'activeTab' => 'billing',
            'reservations' => $reservations,
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
                'monthlyReservations' => collect([]),
                'roomOccupancy' => ['available' => 0, 'occupied' => 0, 'maintenance' => 0],
                'taskStatus' => ['pending' => 0, 'in_progress' => 0, 'completed' => 0],
                'monthlyRevenue' => collect([]),
            ]);
        }

        // Aylık rezervasyon verileri
        $monthlyReservations = Reservation::where('hotel_id', $hotelId)
            ->whereYear('created_at', now()->year)
            ->selectRaw('EXTRACT(MONTH FROM created_at) as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

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

        // Aylık gelir
        $monthlyRevenue = Reservation::where('hotel_id', $hotelId)
            ->whereYear('created_at', now()->year)
            ->where('status', 'confirmed')
            ->selectRaw('EXTRACT(MONTH FROM created_at) as month, SUM(total_price) as revenue')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('admin.analytics', [
            'role' => 'Yönetim',
            'activeTab' => 'analytics',
            'monthlyReservations' => $monthlyReservations,
            'roomOccupancy' => $roomOccupancy,
            'taskStatus' => $taskStatus,
            'monthlyRevenue' => $monthlyRevenue,
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
            Message::create([
                'hotel_id' => $hotelId,
                'from_user_id' => $user->id,
                'to_user_id' => $validated['to_user_id'],
                'subject' => $validated['subject'] ?? null,
                'content' => $validated['content'],
                'type' => $validated['type'] ?? 'internal',
                'priority' => $validated['priority'] ?? 'medium',
                'is_read' => false,
            ]);

            return redirect()->route('admin.messages')->with('success', 'Mesaj başarıyla gönderildi.');
        } catch (\Exception $e) {
            return redirect()->route('admin.messages')->with('error', 'Mesaj gönderilirken bir hata oluştu.');
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

    public function updateReservation(Request $request, Reservation $reservation)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled',
        ]);

        try {
            $reservation->update($validated);
            return redirect()->route('admin.reservations')->with('success', 'Rezervasyon başarıyla güncellendi.');
        } catch (\Exception $e) {
            return redirect()->route('admin.reservations')->with('error', 'Rezervasyon güncellenirken bir hata oluştu.');
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

        // Yeni rezervasyonlar (pending)
        $newReservations = Reservation::where('hotel_id', $hotelId)
            ->where('status', 'pending')
            ->with(['user', 'room'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($reservation) {
                return [
                    'type' => 'reservation',
                    'title' => 'Yeni Rezervasyon: ' . ($reservation->user->name ?? 'Bilinmeyen'),
                    'description' => 'Oda ' . ($reservation->room->room_number ?? 'N/A') . ' - ' . $reservation->check_in_date->format('d.m.Y'),
                    'time' => $reservation->created_at,
                    'icon' => 'calendar',
                    'color' => 'green',
                    'data' => $reservation,
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
            ->merge($newReservations)
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
}
