<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDepositsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deposits', function (Blueprint $table) {
            $table->id();
            $table->char('tracking_code', 10);          // tracking code

            $table->decimal('action_asset', 20, 20, true);
            $table->decimal('asset', 20, 20);            // total balance
            $table->decimal('action_liability', 20, 20, true);
            $table->decimal('liability', 20, 20);        // blocked balance
            $table->decimal('equity', 20, 20);           // free balance

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
        Schema::dropIfExists('deposits');
    }
}
