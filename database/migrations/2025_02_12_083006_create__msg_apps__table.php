<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('MsgUsers', function (Blueprint $table) {
            $table->id();
            $table->string('user_id')->unique();
            $table->string('email')->nullable();
            $table->string('password');
            $table->integer('failed_attempts')->default(0);
            $table->integer('lock_until')->default(0);
            $table->integer('last_attempt')->default(0);
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('MsgUsers');
    }
};
