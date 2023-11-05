<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users_chats', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('chat_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->enum('chat_role', ['admin', 'moderator', 'user'])->default('user');
            $table->timestamps();

            $table->foreign('chat_id')->references('id')->on('chats')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');

            $table->unique(['chat_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_chats');
    }
};
