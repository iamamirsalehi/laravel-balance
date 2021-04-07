<?php


namespace Iamamirsalehi\LaravelBalance\Utilities;


class CodeGenerator
{
    public static function make()
    {
        $number = mt_rand(100000, 999999);

        return $number;
    }

}