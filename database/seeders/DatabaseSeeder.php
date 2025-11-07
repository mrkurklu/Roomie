<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Önce Rolleri oluştur
        $this->call(RolesSeeder::class);

        // Super Admin Kullanıcısını Oluştur
        $superadmin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@roomie.com',
            'password' => Hash::make('password'), // Şifre: password
        ]);
        $superadminRole = DB::table('roles')->where('name', 'superadmin')->first();
        DB::table('role_user')->insert([
            'user_id' => $superadmin->id,
            'role_id' => $superadminRole->id,
        ]);


        // === Örnek Otel ve Oda Verileri ===

        $hotelId = DB::table('hotels')->insertGetId([
            'name' => 'Roomie Hotel Istanbul', 'address' => 'Istanbul, Turkey', 'phone' => '901234567890', 'email' => 'contact@roomieistanbul.com', 'created_at' => now(), 'updated_at' => now(),
        ]);
        $singleRoomTypeId = DB::table('room_types')->insertGetId([
            'hotel_id' => $hotelId, 'name' => 'Single Room', 'description' => 'A cozy room for a single traveler.', 'price_per_night' => 150.00, 'capacity' => 1, 'created_at' => now(), 'updated_at' => now(),
        ]);
        $doubleRoomTypeId = DB::table('room_types')->insertGetId([
            'hotel_id' => $hotelId, 'name' => 'Double Room', 'description' => 'A spacious room for two.', 'price_per_night' => 250.00, 'capacity' => 2, 'created_at' => now(), 'updated_at' => now(),
        ]);
        DB::table('rooms')->insert([
            ['hotel_id' => $hotelId, 'room_type_id' => $doubleRoomTypeId, 'room_number' => '101', 'image_path' => 'images/rooms/room-1.jpeg', 'status' => 'available', 'created_at' => now(), 'updated_at' => now()],
            ['hotel_id' => $hotelId, 'room_type_id' => $singleRoomTypeId, 'room_number' => '102', 'image_path' => 'images/rooms/room-2.jpeg', 'status' => 'available', 'created_at' => now(), 'updated_at' => now()],
            ['hotel_id' => $hotelId, 'room_type_id' => $doubleRoomTypeId, 'room_number' => '201', 'image_path' => 'images/rooms/room-3.jpeg', 'status' => 'available', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
