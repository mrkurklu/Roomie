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
            ->with(['roles'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $stats = [
            'total' => User::where('hotel_id', $hotelId)->whereHas('roles', function($q) {
                $q->where('name', 'misafir');
            })->count(),
        ];

        return view('admin.guests', [
            'role' => 'Yönetim',
            'activeTab' => 'guests',
            'guests' => $guests,
            'stats' => $stats,
        ]);
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
            $toUserLanguage = $toUser->language ?? 'tr';
            
            // Mesajın dilini tespit et
            $detectedLanguage = \App\Services\TranslationService::detectLanguage($validated['content']);
            
            // Alıcının diline çevir
            $translatedContent = $toUserLanguage !== $detectedLanguage 
                ? \App\Services\TranslationService::translate($validated['content'], $toUserLanguage, $detectedLanguage)
                : $validated['content'];

            Message::create([
                'hotel_id' => $hotelId,
                'from_user_id' => $user->id,
                'to_user_id' => $validated['to_user_id'],
                'subject' => $validated['subject'] ?? null,
                'content' => $translatedContent,
                'original_content' => $validated['content'],
                'original_language' => $detectedLanguage,
                'translated_content' => $translatedContent,
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

        $events = $hotelId ? Event::where('hotel_id', $hotelId)
            ->orderBy('priority', 'desc')
            ->orderBy('start_date', 'asc')
            ->paginate(15) : collect([]);

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

            return redirect()->route('admin.events')->with('success', 'Etkinlik başarıyla oluşturuldu.');
        } catch (\Exception $e) {
            return redirect()->route('admin.events')->with('error', 'Etkinlik oluşturulurken bir hata oluştu.');
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
            return redirect()->route('admin.events')->with('success', 'Etkinlik başarıyla güncellendi.');
        } catch (\Exception $e) {
            return redirect()->route('admin.events')->with('error', 'Etkinlik güncellenirken bir hata oluştu.');
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
}
