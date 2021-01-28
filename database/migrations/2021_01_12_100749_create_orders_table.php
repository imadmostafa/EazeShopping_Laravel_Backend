<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();//location of customer to deliver to ;

            $table->integer('amount')->nullable();//price
            $table->integer('item_count')->nullable();//price

            $table->unsignedBigInteger('customer_id');
            $table->foreign('customer_id')->references('id')->on('users');

            $table->unsignedBigInteger('cashier_id')->nullable();
            $table->foreign('cashier_id')->references('id')->on('users');

            $table->unsignedBigInteger('store_id');//from which store is the delivery
            $table->foreign('store_id')->references('id')->on('users');

            $table->boolean('isDelivered');

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
}
