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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger("status")->default(0);
            $table->string("payment_intent_id");
            //
            $table->string("customer_first_name");
            $table->string("customer_last_name");
            $table->string("customer_emil");
            $table->string("customer_phone");
            //
            $table->string("shipping_address");
            $table->string("shipping_city");
            $table->string("shipping_state");
            $table->string("shipping_postal_code");
            //
            $table->string("billing_address");
            //
            $table->tinyInteger("quantity")->min(1);
            $table->decimal("amount", 8, 2);
            $table->decimal("shipping", 8, 2);
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
