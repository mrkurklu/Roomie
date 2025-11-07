<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('resources')) {
            return;
        }
        
        Schema::create('resources', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('category', ['equipment', 'supply', 'service', 'other'])->default('other');
            $table->integer('quantity')->default(0);
            $table->integer('available_quantity')->default(0);
            $table->string('unit')->nullable(); // Örn: adet, kg, litre
            $table->decimal('cost_per_unit', 10, 2)->nullable();
            $table->enum('status', ['available', 'low_stock', 'out_of_stock'])->default('available');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resources');
    }
};

