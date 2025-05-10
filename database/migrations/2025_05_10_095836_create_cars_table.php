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
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->string('license_plate')->nullable();
            $table->string('manufacturer');
            $table->string('model');
            $table->integer('year')->nullable();
            $table->string('color')->nullable();
            $table->string('location')->nullable();
            $table->date('missing_date')->nullable();
            $table->enum('status', ['missing', 'found', 'stolen'])->default('missing');
            $table->string('owner_name')->nullable();
            $table->string('owner_contact')->nullable();
            $table->text('description')->nullable();
            $table->text('notes')->nullable();
            $table->string('source')->nullable();
            $table->boolean('is_approved')->default(false);
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
