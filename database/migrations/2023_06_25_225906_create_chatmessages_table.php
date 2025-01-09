<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('cloudapi_id');
            $table->string('phone_number');
            $table->text('message_history');
            $table->text('user_notes');
            $table->text('follow_up');
            $table->integer('pinned')->default(0);
            $table->integer('counts')->default(0);
            $table->integer('notification')->default(0);
            $table->timestamp('timestamp')->default(now());
            $table->timestamps();

            $table->foreign('cloudapi_id')->references('id')->on('cloudapis')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chat_messages');
    }
};
