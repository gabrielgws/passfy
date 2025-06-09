<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ticket_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->string('name'); // Ex: "Inteira", "Meia", "VIP"
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2)->default(0);
            $table->integer('quantity_available')->default(0);
            $table->integer('quantity_sold')->default(0);
            $table->integer('min_purchase')->default(1);
            $table->integer('max_purchase')->default(10);
            $table->boolean('is_visible')->default(true);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->datetime('sales_start_at')->nullable();
            $table->datetime('sales_end_at')->nullable();
            $table->timestamps();

            $table->index(['event_id', 'is_active', 'is_visible']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ticket_types');
    }
};
