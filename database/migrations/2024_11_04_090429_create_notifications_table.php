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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->string('title_ru')->nullable();
            $table->string('title_en')->nullable();
            $table->string('short_description');
            $table->string('short_description_ru')->nullable();
            $table->string('short_description_en')->nullable();
            $table->string('description');
            $table->string('description_ru')->nullable();
            $table->string('description_en')->nullable();
            $table->string('image');
            $table->string('image_ru')->nullable();
            $table->string('image_en')->nullable();
            $table->enum('type', ['global', 'personal'])->default('personal');
            $table->enum('is_view', [0, 1])->default(0);
            $table->enum('enable', [0, 1])->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
