<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('msg_conversations', function (Blueprint $table) {
            $table->id();
            $table->string('convo_id');
            $table->string('sender');
            $table->text('conversation');
            $table->text('filePath');
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('msg_conversations');
    }
};
