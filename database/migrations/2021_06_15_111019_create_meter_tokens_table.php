<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMeterTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meter_tokens', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('meterNo');
            $table->string('token')->nullable();
            $table->timestamp('CreateDate')->nullable();
            $table->timestamp('EndDate')->nullable();
            $table->boolean('status')->default(false);
            $table->string('tokenStatus');
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
        Schema::dropIfExists('meter_tokens');
    }
}
