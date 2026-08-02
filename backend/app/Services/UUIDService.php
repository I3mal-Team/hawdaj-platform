<?php

namespace App\Services;

class UUIDService
{

    public function __construct($prefix = '', $entropy = false)
    {
        $this->uuid = uniqid($prefix, $entropy);
    }

    public function limit($length, $start = 0)
    {
        return substr($this->uuid, $start, $length);

    }

}
