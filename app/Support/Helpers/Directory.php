<?php

namespace App\Support\Helpers;

class Directory
{
    public static function base_path($path = ''): string
    {
        return app()->basePath($path);
    }
}
