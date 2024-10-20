<?php

namespace App\Utils;

class CpfFormatter
{
    public static function format(string $cpf): string
    {
        return preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "$1.$2.$3-$4", $cpf);
    }
    public static function unformat(string $cpf): string
    {
        return preg_replace('/[^0-9]/', '', $cpf);
    }
}
