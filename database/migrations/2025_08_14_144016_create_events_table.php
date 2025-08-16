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
            $table->foreignId('organizer_id')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->string('category');
            $table->enum('type', ['free', 'paid'])->default('free');
            $table->decimal('price', 10, 2)->nullable();
            $table->integer('max_tickets')->nullable();
            $table->integer('tickets_sold')->default(0);
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->string('location');
            $table->string('address')->nullable();
            $table->boolean('approved')->default(false);
            $table->string('image_path')->nullable();
            $table->text('terms_conditions')->nullable();
            $table->timestamps();
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
