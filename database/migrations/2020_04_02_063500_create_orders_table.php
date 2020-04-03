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
            $table->bigIncrements('id');
            $table->unsignedBigInteger('hotel_id');
            $table->unsignedBigInteger('guest_id');
            $table->enum('status', ['pending', 'paid', 'ready', 'checkin', 'checkout', 'expired', 'cancelled']);
            $table->timestamp('checkin_at');
            $table->timestamp('checkout_at')->useCurrent();
            $table->timestamps();
            // index foriegn
            $table->foreign('hotel_id')->references('id')->on('hotels')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('guest_id')->references('id')->on('guests')
                ->onUpdate('cascade')
                ->onDelete('cascade');
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
