<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('feedbacks', 'title')) {
            Schema::table('feedbacks', function (Blueprint $table) {
                $table->string('title')->nullable()->after('rating');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('feedbacks', 'title')) {
            Schema::table('feedbacks', function (Blueprint $table) {
                $table->dropColumn('title');
            });
        }
    }
};
