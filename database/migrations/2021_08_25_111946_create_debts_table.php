<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDebtsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('debts', function (Blueprint $table) {
            $table->id();
            $table->integer('status');
            $table->decimal('amount', 8, 2);
            $table->string('payment_method');
            $table->string('wallet_id');
            $table->string('payment_reference')->nullable();
            $table->string('concept')->nullable();
            $table->integer('creditor');
            $table->integer('debtor');
            $table->json('payload')->nullable();
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
        Schema::dropIfExists('debts');
    }
}
