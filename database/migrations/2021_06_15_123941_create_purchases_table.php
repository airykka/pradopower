<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->string('meter_number');
            $table->string('customer_number');
            $table->string('customer_name');
            $table->text('customer_addr');
            $table->string('token');
            $table->decimal('total_paid', 20,4);
            $table->decimal('total_unit', 10,4);
            $table->decimal('price', 20,4);
            $table->decimal('vat', 10,4);
            $table->string('currency');
            $table->string('unit');
            $table->string('TaskNo');
            $table->string('gen_datetime');
            $table->string('gen_user');
            $table->string('company');
            $table->string('tid_datetime');
            $table->boolean('status')->default(false);
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
        Schema::dropIfExists('purchases');
    }
}
