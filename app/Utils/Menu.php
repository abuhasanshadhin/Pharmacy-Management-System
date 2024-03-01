<?php

namespace App\Utils;

class Menu
{
    public static function items()
    {
        return require __DIR__ . '/menus.php';
    }
}
