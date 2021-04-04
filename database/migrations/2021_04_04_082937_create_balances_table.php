<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBalancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('balances', function (Blueprint $table) {
            $table->id();
            $table->char('balance_code', 10);          // tracking code

            $table->unsignedBigInteger('actionable_id');
            $table->char('actionable_type');

            $table->float('balance_action_asset');
            $table->float('balance_asset');            // totoal balance
            $table->float('balance_action_liability'); // blocked balance
            $table->float('balance_equity');           // free balance

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
         
            $table->unsignedBigInteger('coin_id');            
            $table->foreign('coin_id')->references('id')->on('coins')->onDelete('cascade');            
         
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
        Schema::dropIfExists('balances');
    }
}
