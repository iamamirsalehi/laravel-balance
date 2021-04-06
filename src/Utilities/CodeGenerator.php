<?php


namespace Iamamirsalehi\LaravelBalance\src\Utilities;


class CodeGenerator
{
    public static function make()
    {
        $number = mt_rand(100000, 999999);

        return $number;
    }

}