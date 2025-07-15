<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->text('short_description')->nullable();
            $table->string('image')->nullable();
            $table->string('banner')->nullable();
            $table->string('location');
            $table->string('address')->nullable();
            $table->string('city');
            $table->string('state', 2);
            $table->string('zip_code', 10)->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->dateTime('sale_start_date')->nullable();
            $table->dateTime('sale_end_date')->nullable();
            $table->integer('max_tickets')->nullable();
            $table->integer('sold_tickets')->default(0);
            $table->decimal('maintenance_fee_percentage', 5, 2)->default(5.00); // Taxa específica do evento
            $table->enum('status', ['draft', 'published', 'cancelled', 'finished'])->default('draft');
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_free')->default(false);
            $table->timestamps();

            $table->index(['status', 'start_date']);
            $table->index(['city', 'state']);
            $table->index(['is_featured', 'start_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
