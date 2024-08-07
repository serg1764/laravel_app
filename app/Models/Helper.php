<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class Helper
{
    public static function logToDatabase($message, $level = 'info')
    {
        DB::table('logs')->insert([
            'message' => print_r($message, true),
            'level' => $level,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

}
