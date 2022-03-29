<?php

class NumberUtilities
{
    public static function formatNumberWithComma($string) {
        return number_format($string,2);
    }
}