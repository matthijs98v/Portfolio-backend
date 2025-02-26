<?php

namespace App\Controllers;

use lib\Router\Request;

class Test
{
    /*
     * Returns all projects
     */
    public static function test(Request $r): array
    {
        return [
            'msg' => "Works!",
            // 'Enviorments' => $_ENV['DB_PASS']
        ];
    }
}
