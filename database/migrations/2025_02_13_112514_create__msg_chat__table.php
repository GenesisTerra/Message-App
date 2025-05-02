<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('msg_chats', function (Blueprint $table) {
            $table->id();
            $table->string('user_id_sender');
            $table->string('receiver')->nullable();
            $table->string('user_id_receiver');
            $table->string('convo_id');
            $table->timestamps();

            // Ensure that the sender and receiver combination is unique
            $table->unique(['user_id_sender', 'user_id_receiver']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('msg_chats');
    }
};
