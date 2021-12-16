<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {


        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->morphs('movable');
            $table->string('gateway');
            $table->string('order_id');
            $table->integer('amount');
            $table->string('description')->nullable();
            $table->string('reference')->nullable();
            $table->string('kind');
            $table->string('status');
            $table->string('error_code')->nullable();
            $table->longText('payload');
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('reservation_id')->constrained('reservations');
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
        Schema::dropIfExists('transactions');
    }
}
