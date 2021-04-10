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
            $table->char('tracking_code', 10);          // tracking code

            $table->decimal('action_asset', 18, 9);
            $table->decimal('asset', 18, 9);            // total balance
            $table->decimal('action_liability', 18, 9);
            $table->decimal('liability', 18, 9);        // blocked balance
            $table->decimal('equity', 18, 9);           // free balance

            $table->tinyInteger('is_admin_confirmed')->default(0);
            $table->dateTime('admin_confirmation_date_time')->nullable();
            $table->tinyInteger('is_admin_rejected')->default(0);
            $table->dateTime('admin_rejection_date_time')->nullable();
            $table->text('is_admin_rejected_description')->nullable();

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
