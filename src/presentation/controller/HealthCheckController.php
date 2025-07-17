<?php

namespace App\Presentation\Controller;

class HealthCheckController
{
    public static function alive() {
        return [
            "status" => "I'm alive"
        ];
    }
}