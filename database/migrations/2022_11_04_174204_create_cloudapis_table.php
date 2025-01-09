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
    Schema::create('cloudapis', function (Blueprint $table) {
        $table->id();
        $table->uuid()->unique();
        $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
        $table->string('name');
        $table->string('phone')->nullable();
        $table->string('user_name')->nullable();
        $table->string('phone_number_id')->nullable();
        $table->string('wa_business_id')->nullable();
        $table->string('meta_app_id')->nullable();
        $table->string('access_token', 255)->nullable();
        $table->integer('status')->default(0); // 0 = session out, 1 = session active
        $table->text('hook_url')->nullable();

        // Additional columns
        $table->string('image')->nullable();
        $table->text('about')->nullable();
        $table->string('address')->nullable();
        $table->text('description')->nullable();
        $table->string('industry')->nullable();
        $table->string('email')->nullable();
        $table->string('website')->nullable();

        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cloudapis');
    }
};
