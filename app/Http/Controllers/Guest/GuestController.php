<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Request as GuestRequest;
use App\Models\Feedback;
use App\Models\Room;
use App\Models\User;
use App\Models\Event;
use App\Models\GuestStay;
use Illuminate\Http\Request;

class GuestController extends Controller
{
    public function welcome()
    {
        $user = auth()->user();
        $hotelId = $user->hotel_id;

        $hotel = $user->hotel;
        
        // Aktif oda rezervasyonu
        $activeStay = GuestStay::where('user_id', $user->id)
            ->where('status', 'checked_in')
            ->with(['room.roomType', 'room.assignedStaff'])
            ->first();
        
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
            'activeStay' => $activeStay,
        ]);
    }
    
    public function events()
    {
        $user = auth()->user();
        $hotelId = $user->hotel_id;

        if (!$hotelId) {
            $events = new \Illuminate\Pagination\LengthAwarePaginator(
                collect([]),
                0,
                12,
                1
            );
        } else {
            $events = Event::where('hotel_id', $hotelId)
                ->where('is_active', true)
                ->where(function($q) {
                    $q->whereNull('end_date')
                      ->orWhere('end_date', '>=', now());
                })
                ->with('images')
                ->orderBy('priority', 'desc')
                ->orderBy('start_date', 'asc')
                ->paginate(12);
        }

        return view('guest.events', [
            'role' => 'Misafir',
            'activeTab' => 'events',
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

        // Misafirin aktif oda rezervasyonunu kontrol et
        $activeStay = GuestStay::where('user_id', $user->id)
            ->where('status', 'checked_in')
            ->with('room.assignedStaff')
            ->first();

        // Sadece odaya atanmış personel ile mesajlaşmayı göster
        $assignedStaffId = null;
        if ($activeStay && $activeStay->room && $activeStay->room->assigned_staff_id) {
            $assignedStaff = User::find($activeStay->room->assigned_staff_id);
            if ($assignedStaff && $assignedStaff->hotel_id == $hotelId && $assignedStaff->hasRole('personel')) {
                $assignedStaffId = $assignedStaff->id;
            }
        }

        // Sadece misafir ve odaya atanmış personel arasındaki mesajları göster
        if ($assignedStaffId) {
            // sender_id kolonunu direkt kullan (veritabanında bu kolon var)
            $messages = Message::where('hotel_id', $hotelId)
                ->where('type', 'guest')
                ->where(function($q) use ($user, $assignedStaffId) {
                    // Misafirden personele veya personelden misafire mesajlar
                    // sender_id ve from_user_id aynı şey (model'de map ediliyor)
                    $q->where(function($subQ) use ($user, $assignedStaffId) {
                        $subQ->where('sender_id', $user->id)
                             ->where('to_user_id', $assignedStaffId);
                    })->orWhere(function($subQ) use ($user, $assignedStaffId) {
                        $subQ->where('sender_id', $assignedStaffId)
                             ->where('to_user_id', $user->id);
                    });
                })
                ->with(['fromUser', 'toUser'])
                ->orderBy('created_at', 'asc')
                ->get();
        } else {
            // Odaya atanmış personel yoksa boş mesaj listesi
            $messages = collect([]);
        }

        // Debug: Detaylı log
        \Log::info('Chat messages debug', [
            'user_id' => $user->id,
            'hotel_id' => $hotelId,
            'assigned_staff_id' => $assignedStaffId,
            'messages_count' => $messages->count(),
            'active_stay_exists' => $activeStay ? 'yes' : 'no',
            'room_id' => $activeStay && $activeStay->room ? $activeStay->room->id : null,
            'room_assigned_staff_id' => $activeStay && $activeStay->room ? $activeStay->room->assigned_staff_id : null,
            'message_ids' => $messages->pluck('id')->toArray(),
        ]);

        // Mesajları kullanıcının diline göre çevir
        $messages->transform(function($message) use ($userLanguage, $user) {
            // Eğer mesaj kullanıcıdan geliyorsa, orijinal içeriği göster
            // sender_id veya from_user_id kullan (model'de map ediliyor)
            $senderId = $message->sender_id ?? $message->from_user_id ?? ($message->attributes['sender_id'] ?? null);
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
            'title' => 'nullable|string|max:255',
            'comment' => 'nullable|string|max:1000',
            'category' => 'nullable|in:service,cleanliness,comfort,value,other',
        ]);

        try {
            $feedback = Feedback::create([
                'hotel_id' => $hotelId,
                'user_id' => $user->id,
                'guest_name' => $user->name,
                'guest_email' => $user->email,
                'rating' => $validated['rating'],
                'title' => $validated['title'] ?? null,
                'comment' => $validated['comment'] ?? null,
                'category' => $validated['category'] ?? 'service',
                'is_public' => false,
                'is_responded' => false,
            ]);

            // Admin'e bildirim gönder (mesaj olarak)
            $admin = User::where('hotel_id', $hotelId)
                ->whereHas('roles', function($q) {
                    $q->where('name', 'admin')->orWhere('name', 'yönetici');
                })
                ->first();

            if ($admin) {
                $categoryText = [
                    'service' => 'Hizmet',
                    'cleanliness' => 'Temizlik',
                    'comfort' => 'Konfor',
                    'value' => 'Değer',
                    'other' => 'Diğer'
                ];

                $stars = str_repeat('⭐', $validated['rating']);
                $messageContent = "Yeni Geri Bildirim\n\n";
                $messageContent .= "Misafir: {$user->name}\n";
                $messageContent .= "Puan: {$stars} ({$validated['rating']}/5)\n";
                $messageContent .= "Kategori: " . ($categoryText[$validated['category'] ?? 'service'] ?? 'Diğer') . "\n";
                if ($validated['title'] ?? null) {
                    $messageContent .= "Başlık: {$validated['title']}\n";
                }
                if ($validated['comment'] ?? null) {
                    $messageContent .= "Yorum: " . \Illuminate\Support\Str::limit($validated['comment'], 200) . "\n";
                }

                Message::create([
                    'hotel_id' => $hotelId,
                    'sender_id' => $user->id,
                    'from_user_id' => $user->id,
                    'to_user_id' => $admin->id,
                    'subject' => 'Yeni Geri Bildirim: ' . ($validated['title'] ?? 'Geri Bildirim'),
                    'content' => $messageContent,
                    'original_content' => $messageContent,
                    'original_language' => 'tr',
                    'translated_content' => $messageContent,
                    'type' => 'system',
                    'priority' => 'medium',
                    'is_read' => false,
                ]);
            }

            return redirect()->route('guest.feedback')->with('success', 'Geri bildiriminiz başarıyla gönderildi ve yönetime iletildi.');
        } catch (\Exception $e) {
            \Log::error('Geri bildirim gönderme hatası: ' . $e->getMessage(), [
                'exception' => $e,
                'user_id' => auth()->id(),
                'request_data' => $request->all()
            ]);
            return redirect()->route('guest.feedback')->with('error', 'Geri bildirim gönderilirken bir hata oluştu: ' . $e->getMessage());
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
            
            // Mesajın dilini tespit et
            $detectedLanguage = \App\Services\TranslationService::detectLanguage($validated['content']);
            
            // Misafirin aktif oda rezervasyonunu kontrol et
            $activeStay = GuestStay::where('user_id', $user->id)
                ->where('status', 'checked_in')
                ->with('room.assignedStaff')
                ->first();

            $messageSent = false;
            $targetStaff = null;

            // Eğer misafirin aktif bir oda rezervasyonu varsa ve oda için atanmış personel varsa
            if ($activeStay && $activeStay->room && $activeStay->room->assigned_staff_id) {
                $targetStaff = User::find($activeStay->room->assigned_staff_id);
                
                // Atanmış personel hala aktif ve aynı otele ait mi kontrol et
                if ($targetStaff && $targetStaff->hotel_id == $hotelId && $targetStaff->hasRole('personel')) {
                    $staffLanguage = $targetStaff->language ?? 'tr';
                    
                    // Personelin diline çevir
                    $translatedContent = $staffLanguage !== $detectedLanguage 
                        ? \App\Services\TranslationService::translate($validated['content'], $staffLanguage, $detectedLanguage)
                        : $validated['content'];

                    $message = Message::create([
                        'hotel_id' => $hotelId,
                        'sender_id' => $user->id, // sender_id direkt kullan
                        'from_user_id' => $user->id, // from_user_id de set et (model map ediyor)
                        'to_user_id' => $targetStaff->id,
                        'subject' => $validated['subject'] ?? 'Misafir Mesajı (Oda: ' . $activeStay->room->room_number . ')',
                        'content' => $translatedContent,
                        'original_content' => $validated['content'],
                        'original_language' => $detectedLanguage,
                        'translated_content' => $translatedContent,
                        'type' => 'guest',
                        'priority' => 'medium',
                        'is_read' => false,
                    ]);
                    
                    \Log::info('Guest message sent', [
                        'message_id' => $message->id,
                        'from_user_id' => $user->id,
                        'to_user_id' => $targetStaff->id,
                        'sender_id' => $message->sender_id ?? $message->attributes['sender_id'] ?? null,
                        'content' => substr($translatedContent, 0, 50),
                    ]);
                    
                    $messageSent = true;
                }
            }

            // Eğer odaya atanmış personel yoksa hata ver
            if (!$messageSent) {
                if (!$activeStay || !$activeStay->room) {
                    return redirect()->route('guest.chat')->with('error', 'Mesaj gönderilemedi. Aktif bir oda rezervasyonunuz bulunmamaktadır.');
                }
                
                if (!$activeStay->room->assigned_staff_id) {
                    return redirect()->route('guest.chat')->with('error', 'Mesaj gönderilemedi. Odanıza henüz personel atanmamış. Lütfen resepsiyon ile iletişime geçin.');
                }
                
                return redirect()->route('guest.chat')->with('error', 'Mesaj gönderilemedi. Lütfen tekrar deneyin.');
            }

            return redirect()->route('guest.chat')->with('success', 'Mesajınız başarıyla gönderildi.');
        } catch (\Exception $e) {
            \Log::error('Mesaj gönderme hatası (Guest): ' . $e->getMessage(), [
                'exception' => $e,
                'user_id' => auth()->id(),
                'request_data' => $request->all()
            ]);
            return redirect()->route('guest.chat')->with('error', 'Mesaj gönderilirken bir hata oluştu: ' . $e->getMessage());
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
            // Misafirin aktif oda rezervasyonunu kontrol et
            $activeStay = GuestStay::where('user_id', $user->id)
                ->where('status', 'checked_in')
                ->with('room.assignedStaff')
                ->first();

            if (!$activeStay || !$activeStay->room) {
                return redirect()->route('guest.requests')->with('error', 'Talep gönderilemedi. Aktif bir oda rezervasyonunuz bulunmamaktadır.');
            }

            // Odaya atanmış personel var mı kontrol et
            $assignedStaffId = null;
            if ($activeStay->room->assigned_staff_id) {
                $assignedStaff = User::find($activeStay->room->assigned_staff_id);
                if ($assignedStaff && $assignedStaff->hotel_id == $hotelId && $assignedStaff->hasRole('personel')) {
                    $assignedStaffId = $assignedStaff->id;
                }
            }

            if (!$assignedStaffId) {
                return redirect()->route('guest.requests')->with('error', 'Talep gönderilemedi. Odanıza henüz personel atanmamış. Lütfen resepsiyon ile iletişime geçin.');
            }

            GuestRequest::create([
                'hotel_id' => $hotelId,
                'user_id' => $user->id,
                'category' => $validated['type'], // type -> category mapping
                'title' => $validated['title'],
                'description' => $validated['description'],
                'priority' => $validated['priority'] ?? 'medium',
                'status' => 'in_progress',
                'assigned_to' => $assignedStaffId,
            ]);

            return redirect()->route('guest.requests')->with('success', 'Talebiniz başarıyla gönderildi ve odanıza atanmış personele iletildi.');
        } catch (\Exception $e) {
            \Log::error('Talep gönderme hatası (Guest): ' . $e->getMessage(), [
                'exception' => $e,
                'user_id' => auth()->id(),
                'request_data' => $request->all()
            ]);
            return redirect()->route('guest.requests')->with('error', 'Talep gönderilirken bir hata oluştu.');
        }
    }

    public function notifications()
    {
        $user = auth()->user();
        $hotelId = $user->hotel_id;

        if (!$hotelId) {
            return view('guest.notifications', [
                'role' => 'Misafir',
                'activeTab' => 'notifications',
                'notifications' => collect([]),
            ]);
        }

        // Mesajlar (okunmamış)
        $unreadMessages = Message::where('hotel_id', $hotelId)
            ->where(function($q) use ($user) {
                $q->where('from_user_id', $user->id)
                  ->orWhere('to_user_id', $user->id);
            })
            ->where(function($q) {
                $q->whereNull('is_read')->orWhere('is_read', false);
            })
            ->where('type', 'guest')
            ->with(['fromUser', 'toUser'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($message) use ($user) {
                $isFromMe = $message->from_user_id === $user->id;
                return [
                    'type' => 'message',
                    'title' => $isFromMe ? 'Mesajınız gönderildi' : ($message->fromUser->name ?? 'Personel'),
                    'description' => \Illuminate\Support\Str::limit($message->content, 100),
                    'time' => $message->created_at,
                    'icon' => 'message-square',
                    'color' => 'blue',
                    'data' => $message,
                ];
            });

        // Talepler (durum değişiklikleri)
        $requests = GuestRequest::where('hotel_id', $hotelId)
            ->where('user_id', $user->id)
            ->whereIn('status', ['in_progress', 'completed'])
            ->orderBy('updated_at', 'desc')
            ->get()
            ->map(function($request) {
                $statusText = [
                    'in_progress' => 'Talebiniz işleme alındı',
                    'completed' => 'Talebiniz tamamlandı',
                ];
                return [
                    'type' => 'request',
                    'title' => $statusText[$request->status] ?? 'Talep durumu güncellendi',
                    'description' => \Illuminate\Support\Str::limit($request->title . ': ' . ($request->description ?? ''), 100),
                    'time' => $request->updated_at,
                    'icon' => 'concierge-bell',
                    'color' => $request->status === 'completed' ? 'green' : 'orange',
                    'data' => $request,
                ];
            });

        // Yeni etkinlikler
        $newEvents = Event::where('hotel_id', $hotelId)
            ->where('is_active', true)
            ->where('created_at', '>=', now()->subDays(7))
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($event) {
                return [
                    'type' => 'event',
                    'title' => 'Yeni Etkinlik: ' . $event->title,
                    'description' => \Illuminate\Support\Str::limit($event->description ?? 'Açıklama yok', 100),
                    'time' => $event->created_at,
                    'icon' => 'calendar',
                    'color' => 'purple',
                    'data' => $event,
                ];
            });

        // Tüm bildirimleri birleştir ve tarihe göre sırala
        $notifications = collect()
            ->merge($unreadMessages)
            ->merge($requests)
            ->merge($newEvents)
            ->sortByDesc('time')
            ->values();

        return view('guest.notifications', [
            'role' => 'Misafir',
            'activeTab' => 'notifications',
            'notifications' => $notifications,
        ]);
    }
}
