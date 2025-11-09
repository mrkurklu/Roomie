<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Request as GuestRequest;
use App\Models\Feedback;
use App\Models\Room;
use App\Models\User;
use App\Models\Event;
use Illuminate\Http\Request;

class GuestController extends Controller
{
    public function welcome()
    {
        $user = auth()->user();
        $hotelId = $user->hotel_id;

        $hotel = $user->hotel;
        
        $events = $hotelId ? Event::where('hotel_id', $hotelId)
            ->where('is_active', true)
            ->where(function($q) {
                $q->whereNull('end_date')
                  ->orWhere('end_date', '>=', now());
            })
            ->orderBy('priority', 'desc')
            ->orderBy('start_date', 'asc')
            ->take(10)
            ->get() : collect([]);

        return view('guest.welcome', [
            'role' => 'Misafir',
            'activeTab' => 'welcome',
            'hotel' => $hotel,
            'events' => $events,
        ]);
    }
    
    public function showEvent(Event $event)
    {
        $user = auth()->user();
        $hotelId = $user->hotel_id;

        // Etkinliğin bu otelin etkinliği olduğunu kontrol et
        if ($event->hotel_id !== $hotelId) {
            abort(404);
        }

        // Etkinliğin aktif olduğunu kontrol et
        if (!$event->is_active) {
            abort(404);
        }

        return view('guest.event-detail', [
            'role' => 'Misafir',
            'activeTab' => 'welcome',
            'event' => $event,
        ]);
    }
    
    public function chat()
    {
        $user = auth()->user();
        $hotelId = $user->hotel_id;
        $userLanguage = $user->language ?? 'tr';

        if (!$hotelId) {
            return view('guest.chat', [
                'role' => 'Misafir',
                'activeTab' => 'chat',
                'messages' => collect([]),
            ]);
        }

        $messages = Message::where('hotel_id', $hotelId)
            ->where(function($q) use ($user) {
                $q->where('from_user_id', $user->id)
                  ->orWhere('to_user_id', $user->id);
            })
            ->where('type', 'guest')
            ->with(['fromUser', 'toUser'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

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

        return view('guest.chat', [
            'role' => 'Misafir',
            'activeTab' => 'chat',
            'messages' => $messages,
        ]);
    }
    
    public function requests()
    {
        $user = auth()->user();
        $hotelId = $user->hotel_id;

        $requests = GuestRequest::where('hotel_id', $hotelId)
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $stats = [
            'total' => GuestRequest::where('hotel_id', $hotelId)->where('user_id', $user->id)->count(),
            'pending' => GuestRequest::where('hotel_id', $hotelId)->where('user_id', $user->id)->where('status', 'pending')->count(),
            'in_progress' => GuestRequest::where('hotel_id', $hotelId)->where('user_id', $user->id)->where('status', 'in_progress')->count(),
            'completed' => GuestRequest::where('hotel_id', $hotelId)->where('user_id', $user->id)->where('status', 'completed')->count(),
        ];

        return view('guest.requests', [
            'role' => 'Misafir',
            'activeTab' => 'requests',
            'requests' => $requests,
            'stats' => $stats,
        ]);
    }
    
    public function services()
    {
        $user = auth()->user();
        $hotel = $user->hotel;

        return view('guest.services', [
            'role' => 'Misafir',
            'activeTab' => 'services',
            'hotel' => $hotel,
        ]);
    }
    
    public function amenities()
    {
        $user = auth()->user();
        $hotel = $user->hotel;

        return view('guest.amenities', [
            'role' => 'Misafir',
            'activeTab' => 'amenities',
            'hotel' => $hotel,
        ]);
    }
    
    public function feedback()
    {
        $user = auth()->user();
        $hotelId = $user->hotel_id;

        if (!$hotelId) {
            return view('guest.feedback', [
                'role' => 'Misafir',
                'activeTab' => 'feedback',
                'feedbacks' => collect([]),
            ]);
        }

        try {
            $feedbacks = Feedback::where('hotel_id', $hotelId)
                ->where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        } catch (\Exception $e) {
            $feedbacks = collect([]);
        }

        return view('guest.feedback', [
            'role' => 'Misafir',
            'activeTab' => 'feedback',
            'feedbacks' => $feedbacks,
        ]);
    }

    public function storeFeedback(Request $request)
    {
        $user = auth()->user();
        $hotelId = $user->hotel_id;

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'category' => 'nullable|in:service,cleanliness,comfort,value,other',
        ]);

        try {
            Feedback::create([
                'hotel_id' => $hotelId,
                'user_id' => $user->id,
                'guest_name' => $user->name,
                'guest_email' => $user->email,
                'rating' => $validated['rating'],
                'comment' => $validated['comment'] ?? null,
                'category' => $validated['category'] ?? 'service',
                'is_public' => false,
                'is_responded' => false,
            ]);

            return redirect()->route('guest.feedback')->with('success', 'Geri bildiriminiz başarıyla gönderildi.');
        } catch (\Exception $e) {
            return redirect()->route('guest.feedback')->with('error', 'Geri bildirim gönderilirken bir hata oluştu.');
        }
    }

    public function storeMessage(Request $request)
    {
        $user = auth()->user();
        $hotelId = $user->hotel_id;

        if (!$hotelId) {
            return redirect()->route('guest.chat')->with('error', 'Mesaj gönderilemedi.');
        }

        $validated = $request->validate([
            'content' => 'required|string|max:1000',
            'subject' => 'nullable|string|max:255',
        ]);

        try {
            // Kullanıcının dil ayarını al
            $userLanguage = $user->language ?? 'tr';
            
            // Mesajın dilini tespit et
            $detectedLanguage = \App\Services\TranslationService::detectLanguage($validated['content']);
            
            // Misafir mesajlarını tüm personellere gönder (personel rolüne sahip kullanıcılar)
            $staffMembers = User::where('hotel_id', $hotelId)
                ->whereHas('roles', function($q) {
                    $q->where('name', 'personel');
                })
                ->get();

            if ($staffMembers->isEmpty()) {
                // Personel yoksa yöneticiye gönder
                $admin = User::where('hotel_id', $hotelId)
                    ->whereHas('roles', function($q) {
                        $q->whereIn('name', ['superadmin', 'müdür']);
                    })
                    ->first();

                if ($admin) {
                    $adminLanguage = $admin->language ?? 'tr';
                    
                    // Admin'in diline çevir
                    $translatedContent = $adminLanguage !== $detectedLanguage 
                        ? \App\Services\TranslationService::translate($validated['content'], $adminLanguage, $detectedLanguage)
                        : $validated['content'];

                    Message::create([
                        'hotel_id' => $hotelId,
                        'from_user_id' => $user->id,
                        'to_user_id' => $admin->id,
                        'subject' => $validated['subject'] ?? 'Misafir Mesajı',
                        'content' => $translatedContent,
                        'original_content' => $validated['content'],
                        'original_language' => $detectedLanguage,
                        'translated_content' => $translatedContent,
                        'type' => 'guest',
                        'priority' => 'medium',
                        'is_read' => false,
                    ]);
                }
            } else {
                // Her personel için mesaj oluştur ve kendi diline çevir
                foreach ($staffMembers as $staff) {
                    $staffLanguage = $staff->language ?? 'tr';
                    
                    // Personelin diline çevir
                    $translatedContent = $staffLanguage !== $detectedLanguage 
                        ? \App\Services\TranslationService::translate($validated['content'], $staffLanguage, $detectedLanguage)
                        : $validated['content'];

                    Message::create([
                        'hotel_id' => $hotelId,
                        'from_user_id' => $user->id,
                        'to_user_id' => $staff->id,
                        'subject' => $validated['subject'] ?? 'Misafir Mesajı',
                        'content' => $translatedContent,
                        'original_content' => $validated['content'],
                        'original_language' => $detectedLanguage,
                        'translated_content' => $translatedContent,
                        'type' => 'guest',
                        'priority' => 'medium',
                        'is_read' => false,
                    ]);
                }
            }

            return redirect()->route('guest.chat')->with('success', 'Mesajınız başarıyla gönderildi.');
        } catch (\Exception $e) {
            return redirect()->route('guest.chat')->with('error', 'Mesaj gönderilirken bir hata oluştu.');
        }
    }

    public function storeRequest(Request $request)
    {
        $user = auth()->user();
        $hotelId = $user->hotel_id;

        $validated = $request->validate([
            'type' => 'required|in:room_service,housekeeping,maintenance,concierge,other',
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'priority' => 'nullable|in:low,medium,high,urgent',
        ]);

        try {
            GuestRequest::create([
                'hotel_id' => $hotelId,
                'user_id' => $user->id,
                'type' => $validated['type'],
                'title' => $validated['title'],
                'description' => $validated['description'],
                'priority' => $validated['priority'] ?? 'medium',
                'status' => 'pending',
            ]);

            return redirect()->route('guest.requests')->with('success', 'Talebiniz başarıyla gönderildi.');
        } catch (\Exception $e) {
            return redirect()->route('guest.requests')->with('error', 'Talep gönderilirken bir hata oluştu.');
        }
    }
}
