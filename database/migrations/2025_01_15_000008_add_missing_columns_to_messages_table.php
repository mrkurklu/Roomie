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
                if (!Schema::hasColumn('messages', 'from_user_id')) {
                    $table->foreignId('from_user_id')->nullable()->after('hotel_id')->constrained('users')->onDelete('cascade');
                }
                if (!Schema::hasColumn('messages', 'to_user_id')) {
                    $table->foreignId('to_user_id')->nullable()->after('from_user_id')->constrained('users')->onDelete('set null');
                }
                if (!Schema::hasColumn('messages', 'subject')) {
                    $table->string('subject')->nullable()->after('to_user_id');
                }
                if (!Schema::hasColumn('messages', 'content')) {
                    $table->text('content')->nullable()->after('subject');
                }
                if (!Schema::hasColumn('messages', 'type')) {
                    $table->enum('type', ['internal', 'guest', 'system'])->default('internal')->after('content');
                }
                if (!Schema::hasColumn('messages', 'priority')) {
                    $table->enum('priority', ['low', 'medium', 'high'])->default('medium')->after('type');
                }
                if (!Schema::hasColumn('messages', 'is_read')) {
                    $table->boolean('is_read')->default(false)->after('priority');
                }
                if (!Schema::hasColumn('messages', 'read_at')) {
                    $table->timestamp('read_at')->nullable()->after('is_read');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('messages')) {
            Schema::table('messages', function (Blueprint $table) {
                if (Schema::hasColumn('messages', 'is_read')) {
                    $table->dropColumn('is_read');
                }
                if (Schema::hasColumn('messages', 'read_at')) {
                    $table->dropColumn('read_at');
                }
                if (Schema::hasColumn('messages', 'type')) {
                    $table->dropColumn('type');
                }
                if (Schema::hasColumn('messages', 'priority')) {
                    $table->dropColumn('priority');
                }
            });
        }
    }
};

