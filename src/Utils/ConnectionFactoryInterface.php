<?php

declare(strict_types=1);

namespace App\Utils;

interface ConnectionFactoryInterface
{
    public function createConnection(): DB;
}
