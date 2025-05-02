<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('msg_profile', function (Blueprint $table) {
            $table->id();
            $table->string('full_name')->nullable();
            $table->string('user_id')->unique();
            $table->string('email')->nullable();
            $table->string('mobile_number')->nullable();
            $table->string('gender')->nullable();
            $table->date('dob')->nullable();
            // $table->string('profile_picture')->nullable(); 
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('msg_profile');
    }
};
