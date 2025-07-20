<?php

namespace App\Application;

class Request
{
    public function __construct(
        public readonly array $params = [],
        public readonly array $query = [],
        public readonly array $body = [],
    ) {
    }
}
