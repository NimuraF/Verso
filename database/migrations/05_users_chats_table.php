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
            $table->bigInteger('first_user_id')->unsigned();
            $table->bigInteger('second_user_id')->unsigned();
            $table->string('name')->nullable()->default(null);
            $table->timestamps();

            $table->foreign('first_user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('second_user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');

            $table->unique(['first_user_id', 'second_user_id']);
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
