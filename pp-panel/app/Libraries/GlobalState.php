<?php

declare(strict_types=1);

namespace App\Libraries;

class GlobalState
{
    private $state = [];

    public function get(string $key)
    {
        return $this->state[$key] ?? null;
    }

    public function set(string $key, $value)
    {
        $this->state[$key] = $value;
    }

    public function has(string $key)
    {
        return isset($this->state[$key]);
    }
}
