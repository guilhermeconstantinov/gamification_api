<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLuckyNumbersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lucky_numbers', function (Blueprint $table) {
            $table->id();
            $table->string('number')->unique();
            $table->boolean('drawn')->default(false);
            $table->timestamp('date')->nullable();
            $table->foreignId('user_id');
            $table->foreignId('raffle_id')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('raffle_id')->references('id')->on('raffles')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lucky_numbers');
    }
}
