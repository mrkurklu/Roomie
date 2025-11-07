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
                if (!Schema::hasColumn('messages', 'original_content')) {
                    $table->text('original_content')->nullable()->after('content');
                }
                if (!Schema::hasColumn('messages', 'original_language')) {
                    $table->string('original_language', 10)->nullable()->after('original_content');
                }
                if (!Schema::hasColumn('messages', 'translated_content')) {
                    $table->text('translated_content')->nullable()->after('original_language');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('messages')) {
            Schema::table('messages', function (Blueprint $table) {
                if (Schema::hasColumn('messages', 'translated_content')) {
                    $table->dropColumn('translated_content');
                }
                if (Schema::hasColumn('messages', 'original_language')) {
                    $table->dropColumn('original_language');
                }
                if (Schema::hasColumn('messages', 'original_content')) {
                    $table->dropColumn('original_content');
                }
            });
        }
    }
};

