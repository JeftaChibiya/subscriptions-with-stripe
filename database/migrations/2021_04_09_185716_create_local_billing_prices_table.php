<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocalBillingPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('local_billing_prices', function (Blueprint $table) {
            $table->id();
            $table->string('price_id');
            $table->foreignId('product_no')
                ->constrained('local_billing_plans')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->string('product_id');
            $table->string('type');
            $table->string('currency');
            $table->string('unit_amount');
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
        Schema::dropIfExists('local_billing_prices');
        Schema::disableForeignKeyConstraints();
    }
}
