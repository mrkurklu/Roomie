<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    /**
     * Tüm odaları listeleyen sayfayı gösterir.
     */
    public function index()
    {
        $rooms = Room::with('roomType')->latest()->paginate(9);
        return view('rooms.index', ['rooms' => $rooms]);
    }

    /**
     * Belirtilen tek bir odayı gösterir.
     * Laravel, rotadaki {room} parametresini otomatik olarak
     * ID'ye göre bulup $room değişkenine atar (Route Model Binding).
     */
    public function show(Room $room)
    {
        // İlişkili verileri (oda tipi vb.) de yükleyelim.
        $room->load('roomType');

        // Odayı 'rooms.show' view'ına gönderiyoruz.
        return view('rooms.show', ['room' => $room]);
    }
}
