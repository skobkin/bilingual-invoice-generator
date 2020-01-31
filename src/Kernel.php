<?php

namespace App;

class Kernel
{
    public static function getProjectRoot(): string
    {
        return dirname(__DIR__);
    }
}