<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('messages')) {
            // PostgreSQL'de enum constraint'ini düzelt
            // Önce mevcut constraint'i kaldır
            try {
                DB::statement("ALTER TABLE messages DROP CONSTRAINT IF EXISTS messages_type_check;");
            } catch (\Exception $e) {
                // Constraint yoksa hata verme
            }
            
            // Yeni constraint ekle
            DB::statement("ALTER TABLE messages ADD CONSTRAINT messages_type_check CHECK (type IN ('internal', 'guest', 'system'));");
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('messages')) {
            try {
                DB::statement("ALTER TABLE messages DROP CONSTRAINT IF EXISTS messages_type_check;");
            } catch (\Exception $e) {
                // Constraint yoksa hata verme
            }
        }
    }
};


