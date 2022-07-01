<?php

namespace App\Mixins;

class StrMixins
{
    /**
     * @return \Closure
     */
    public function concatStrings(): \Closure
    {
        return function (string $string1, string $string2) {
            return $string1 . ' ' . $string2;
        };
    }

    /**
     * @return \Closure
     */
    public function addPrefix(): \Closure
    {
        return function (string $string, string $prefix = "ABCD-"){
            return $prefix . $string;
        };

    }
}
