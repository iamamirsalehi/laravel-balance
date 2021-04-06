<?php

namespace Database\Seeders;

use Iamamirsalehi\LaravelBalance\Models\Coin;
use Illuminate\Database\Seeder;

class CoinsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data1 = [
            'coin_persian_name' => 'ریال',
            'coin_english_name' => 'IRR',
        ];

        $data2 =            [
            'coin_persian_name' => 'تتر',
            'coin_english_name' => 'tether',
            'coin_website_link' => 'https://tether.to/',
            'coin_is_a_token'   => 1,
        ];


        Coin::updateOrCreate($data1);
        Coin::updateOrCreate($data2);
    }
}
