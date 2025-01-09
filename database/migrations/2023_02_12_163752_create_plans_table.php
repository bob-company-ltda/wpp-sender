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
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->string('title')->nullable();
            $table->string('labelcolor')->nullable();
            $table->string('iconname')->nullable();
            $table->double('price')->nullable();
            $table->integer('is_featured')->default(0);
            $table->integer('is_recommended')->default(0);
            $table->integer('is_trial')->default(0);
            $table->integer('status')->default(0);
            $table->integer('days')->default(0);
            $table->integer('trial_days')->nullable();
            $table->text('data')->nullable();

            $table->timestamps();
            $table->foreign('admin_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('plans');
    }
};
