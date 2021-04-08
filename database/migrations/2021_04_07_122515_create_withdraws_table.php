<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWithdrawsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('withdraws', function (Blueprint $table) {
            $table->id();
            $table->char('balance_code', 10);          // tracking code

            $table->float('balance_action_asset', 8);
            $table->float('balance_asset', 8);            // total balance
            $table->float('balance_action_liability', 8);
            $table->float('balance_liability', 8);        // blocked balance
            $table->float('balance_equity', 8);           // free balance

            $table->tinyInteger('balance_is_admin_confirmed')->default(0);
            $table->dateTime('balance_admin_confirmation_date_time')->nullable();
            $table->tinyInteger('balance_is_admin_rejected')->default(0);
            $table->dateTime('balance_admin_rejection_date_time')->nullable();
            $table->text('balance_is_admin_rejected_description')->nullable();

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
        Schema::dropIfExists('withdraws');
    }
}
