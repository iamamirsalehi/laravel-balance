<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoinsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coins', function (Blueprint $table) {
            $table->id();
            $table->char('coin_persian_name', 64);
            $table->char('coin_english_name', 64);
            $table->text('coin_notice', 1024)->nullable();
            $table->char('coin_website_link')->nullable();
            $table->json('coin_tags')->nullable();
            $table->char('coin_white_paper_link'. 256)->nullable();
            $table->tinyInteger('coin_is_a_token')->default(0); // it can be a token or a coin
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
        Schema::dropIfExists('coins');
    }
}
