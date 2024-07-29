<?php

declare(strict_types=1);

/**
 * This file is part of CodeIgniter 4 framework.
 *
 * (c) CodeIgniter Foundation <admin@codeigniter.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace App\Exceptions;

use CodeIgniter\Exceptions\HTTPExceptionInterface;
use Exception;

class Http400Exception extends Exception implements HTTPExceptionInterface
{
    protected $code = 400;

    public static function Error(?string $message = null)
    {
        return new static($message ?? "");
    }
}
