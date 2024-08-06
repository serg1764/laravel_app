<?php

namespace App\Models\Helpers;

use Illuminate\Database\Eloquent\Model;

class MyHelper extends Model
{
    public static function get_pr($data):void
    {
        echo "<pre>";
        print_r($data);
        echo "</pre>";
    }

}
