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
            $table->foreignId('category_id')->constrained()->onDelete('restrict');
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->string('banner_image')->nullable();
            $table->json('gallery_images')->nullable();
            $table->string('location');
            $table->string('address')->nullable();
            $table->string('city');
            $table->string('state');
            $table->string('zip_code')->nullable();
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->decimal('service_fee_percentage', 5, 2)->default(5.00);
            $table->enum('status', ['draft', 'published', 'cancelled', 'finished'])->default('draft');
            $table->timestamps();
            $table->index(['start_date', 'status']);
            $table->index('slug');
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
