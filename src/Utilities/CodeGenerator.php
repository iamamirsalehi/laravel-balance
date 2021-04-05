<?php


namespace Iamamirsalehi\LaravelBalance\src\Utilities;


class CodeGenerator
{
    public static function make()
    {
        $number = mt_rand(10000, 99999);

        return $number;
    }

}