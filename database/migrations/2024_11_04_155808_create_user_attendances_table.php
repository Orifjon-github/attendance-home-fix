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
        Schema::create('user_attendances', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('branch_id');
            $table->enum('type', ['come', 'went'])->default('come');
            $table->string('long')->nullable();
            $table->string('lat')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_attendances');
    }
};
