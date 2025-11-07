<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('messages')) {
            Schema::table('messages', function (Blueprint $table) {
                // from_user_id kolonu yoksa ekle
                if (!Schema::hasColumn('messages', 'from_user_id')) {
                    // Önce hotel_id kolonunu bul
                    $table->foreignId('from_user_id')->nullable()->after('hotel_id')->constrained('users')->onDelete('cascade');
                }
                
                // to_user_id kolonu yoksa ekle
                if (!Schema::hasColumn('messages', 'to_user_id')) {
                    $table->foreignId('to_user_id')->nullable()->after('from_user_id')->constrained('users')->onDelete('set null');
                }
                
                // subject kolonu yoksa ekle
                if (!Schema::hasColumn('messages', 'subject')) {
                    $table->string('subject')->nullable()->after('to_user_id');
                }
                
                // content kolonu yoksa ekle
                if (!Schema::hasColumn('messages', 'content')) {
                    $table->text('content')->nullable()->after('subject');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('messages')) {
            Schema::table('messages', function (Blueprint $table) {
                if (Schema::hasColumn('messages', 'from_user_id')) {
                    $table->dropForeign(['from_user_id']);
                    $table->dropColumn('from_user_id');
                }
                if (Schema::hasColumn('messages', 'to_user_id')) {
                    $table->dropForeign(['to_user_id']);
                    $table->dropColumn('to_user_id');
                }
                if (Schema::hasColumn('messages', 'subject')) {
                    $table->dropColumn('subject');
                }
                if (Schema::hasColumn('messages', 'content')) {
                    $table->dropColumn('content');
                }
            });
        }
    }
};

