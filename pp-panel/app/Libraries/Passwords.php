<?php

declare(strict_types=1);

namespace App\Libraries;

use CodeIgniter\Shield\Authentication\Passwords as ShieldPasswords;

class Passwords extends ShieldPasswords
{
    public function verify(string $password, string $hash): bool
    {
        if (!str_starts_with($hash, '$')) {
            /** @var \Config\Auth */
            $config = $this->config;

            return hash_equals($hash, crypt($password, $config->oldAuthKey1));
        }

        return password_verify($password, $hash);
    }
}
