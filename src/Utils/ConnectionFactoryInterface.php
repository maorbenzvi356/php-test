<?php

namespace App\Utils;

interface ConnectionFactoryInterface {
    public function createConnection(): DB;
}
