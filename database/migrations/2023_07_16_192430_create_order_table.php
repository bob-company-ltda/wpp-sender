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
        Schema::create('order_manage', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->unsignedBigInteger('cloudapi_id');
            $table->string('recipient_phone');
            $table->string('product_name');
            $table->integer('quantity');
            $table->text('additional_instructions')->nullable();
            $table->timestamp('order_date');
            $table->string('status');
            $table->decimal('total_amount', 8, 2);
            $table->string('delivery_address');
            $table->string('invoices')->nullable();
            // Add any other necessary columns

            $table->foreign('cloudapi_id')->references('id')->on('cloudapis')->onDelete('cascade');
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
        Schema::dropIfExists('orders');
    }
};
