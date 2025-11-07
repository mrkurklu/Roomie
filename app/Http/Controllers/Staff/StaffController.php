<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Message;
use App\Models\Ticket;
use App\Models\Schedule;
use App\Models\Resource;
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
    
    public function inbox()
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

        $messages = Message::where('hotel_id', $hotelId)
            ->where(function($query) use ($user) {
                $query->where('from_user_id', $user->id)
                      ->orWhere('to_user_id', $user->id);
            })
            ->with(['fromUser', 'toUser'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // Mesajları kullanıcının diline göre çevir
        $messages->getCollection()->transform(function($message) use ($userLanguage, $user) {
            // Eğer mesaj kullanıcıdan geliyorsa, orijinal içeriği göster
            if ($message->from_user_id === $user->id) {
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

        return view('staff.inbox', [
            'role' => 'Personel',
            'activeTab' => 'inbox',
            'messages' => $messages,
            'unreadCount' => $unreadCount,
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
            $userLanguage = $user->language ?? 'tr';
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

            return redirect()->route('staff.inbox')->with('success', 'Mesaj başarıyla gönderildi.');
        } catch (\Exception $e) {
            return redirect()->route('staff.inbox')->with('error', 'Mesaj gönderilirken bir hata oluştu.');
        }
    }
}
