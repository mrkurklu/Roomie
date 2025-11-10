<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Staff\StaffController;
use App\Http\Controllers\Guest\GuestController;
use App\Models\Room;

// --- ÖN YÜZ ROTALARI ---
Route::get('/', function () { 
    try {
        $rooms = Room::with('roomType')->take(3)->get(); 
    } catch (\Exception $e) {
        $rooms = collect([]); // Veritabanı bağlantısı yoksa boş koleksiyon
    }
    return view('welcome', ['rooms' => $rooms]); 
})->name('home');
Route::get('/rooms', [RoomController::class, 'index'])->name('rooms.index');
Route::get('/rooms/{room}', [RoomController::class, 'show'])->name('rooms.show');
Route::get('/about-us', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');

// --- KULLANICI GİRİŞİ SONRASI ROTALAR ---
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
require __DIR__.'/auth.php';

// --- YÖNETİM PANELİ ROTALARI ---
Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/tasks', [DashboardController::class, 'tasks'])->name('admin.tasks');
    Route::post('/tasks', [DashboardController::class, 'storeTask'])->name('admin.tasks.store');
    Route::patch('/tasks/{task}', [DashboardController::class, 'updateTask'])->name('admin.tasks.update');
    Route::delete('/tasks/{task}', [DashboardController::class, 'deleteTask'])->name('admin.tasks.delete');
    Route::get('/messages', [DashboardController::class, 'messages'])->name('admin.messages');
    Route::post('/messages', [DashboardController::class, 'storeMessage'])->name('admin.messages.store');
    Route::patch('/messages/{message}/read', [DashboardController::class, 'markMessageRead'])->name('admin.messages.read');
    Route::get('/guests', [DashboardController::class, 'guests'])->name('admin.guests');
    Route::get('/billing', [DashboardController::class, 'billing'])->name('admin.billing');
    Route::get('/analytics', [DashboardController::class, 'analytics'])->name('admin.analytics');
    Route::get('/settings', [DashboardController::class, 'settings'])->name('admin.settings');
    Route::put('/settings', [DashboardController::class, 'updateSettings'])->name('admin.settings.update');
    Route::get('/notifications', [DashboardController::class, 'notifications'])->name('admin.notifications');
    Route::get('/events', [DashboardController::class, 'events'])->name('admin.events');
    Route::post('/events', [DashboardController::class, 'storeEvent'])->name('admin.events.store');
    Route::patch('/events/{event}', [DashboardController::class, 'updateEvent'])->name('admin.events.update');
    Route::delete('/events/{event}', [DashboardController::class, 'deleteEvent'])->name('admin.events.delete');
});

// --- PERSONEL PANELİ ROTALARI ---
Route::prefix('staff')->middleware(['auth'])->group(function () {
    Route::get('/tasks', [StaffController::class, 'tasks'])->name('staff.tasks');
    Route::patch('/tasks/{task}/status', [StaffController::class, 'updateTaskStatus'])->name('staff.tasks.updateStatus');
    Route::get('/schedule', [StaffController::class, 'schedule'])->name('staff.schedule');
    Route::get('/tickets', [StaffController::class, 'tickets'])->name('staff.tickets');
    Route::patch('/tickets/{ticket}/status', [StaffController::class, 'updateTicketStatus'])->name('staff.tickets.updateStatus');
    Route::get('/inbox', [StaffController::class, 'inbox'])->name('staff.inbox');
    Route::post('/inbox', [StaffController::class, 'storeMessage'])->name('staff.inbox.store');
    Route::get('/resources', [StaffController::class, 'resources'])->name('staff.resources');
    Route::get('/events', [StaffController::class, 'events'])->name('staff.events');
    Route::post('/events', [StaffController::class, 'storeEvent'])->name('staff.events.store');
    Route::patch('/events/{event}', [StaffController::class, 'updateEvent'])->name('staff.events.update');
    Route::delete('/events/{event}', [StaffController::class, 'deleteEvent'])->name('staff.events.delete');
});

// --- MİSAFİR PANELİ ROTALARI ---
Route::prefix('guest')->middleware(['auth'])->group(function () {
    Route::get('/welcome', [GuestController::class, 'welcome'])->name('guest.welcome');
    Route::get('/events/{event}', [GuestController::class, 'showEvent'])->name('guest.events.show');
    Route::get('/chat', [GuestController::class, 'chat'])->name('guest.chat');
    Route::post('/chat', [GuestController::class, 'storeMessage'])->name('guest.chat.store');
    Route::get('/requests', [GuestController::class, 'requests'])->name('guest.requests');
    Route::post('/requests', [GuestController::class, 'storeRequest'])->name('guest.requests.store');
    Route::get('/services', [GuestController::class, 'services'])->name('guest.services');
    Route::get('/amenities', [GuestController::class, 'amenities'])->name('guest.amenities');
    Route::get('/feedback', [GuestController::class, 'feedback'])->name('guest.feedback');
    Route::post('/feedback', [GuestController::class, 'storeFeedback'])->name('guest.feedback.store');
    Route::get('/notifications', [GuestController::class, 'notifications'])->name('guest.notifications');
});

