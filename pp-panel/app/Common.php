<?php

// use CodeIgniter\Cache\CacheInterface;
// use CodeIgniter\Config\BaseConfig;
// use CodeIgniter\Config\Factories;
// use CodeIgniter\Cookie\Cookie;
// use CodeIgniter\Cookie\CookieStore;
// use CodeIgniter\Cookie\Exceptions\CookieException;
// use CodeIgniter\Database\BaseConnection;
// use CodeIgniter\Database\ConnectionInterface;
// use CodeIgniter\Debug\Timer;
// use CodeIgniter\Files\Exceptions\FileNotFoundException;
// use CodeIgniter\HTTP\CLIRequest;
// use CodeIgniter\HTTP\Exceptions\HTTPException;
use CodeIgniter\HTTP\Exceptions\RedirectException;
use CodeIgniter\HTTP\IncomingRequest;
// use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Database;
// use CodeIgniter\Model;
// use CodeIgniter\Session\Session;
// use CodeIgniter\Test\TestLogger;
// use Config\App;
// use Config\Database;
// use Config\DocTypes;
// use Config\Logger;
use Config\Services;
// use Config\View;
// use Laminas\Escaper\Escaper;

/**
 * The goal of this file is to allow developers a location
 * where they can overwrite core procedural functions and
 * replace them with their own. This file is loaded during
 * the bootstrap process and is called during the framework's
 * execution.
 *
 * This can be looked at as a `master helper` file that is
 * loaded early on, and may also contain additional functions
 * that you'd like to use throughout your entire application
 *
 * @see: https://codeigniter.com/user_guide/extending/common.html
 */

if (!function_exists('force_https')) {
    /**
     * Used to force a page to be accessed in via HTTPS.
     * Uses a standard redirect, plus will set the HSTS header
     * for modern browsers that support, which gives best
     * protection against man-in-the-middle attacks.
     *
     * @see https://en.wikipedia.org/wiki/HTTP_Strict_Transport_Security
     *
     * @param int $duration How long should the SSL header be set for? (in seconds)
     *                      Defaults to 1 year.
     *
     * @throws HTTPException
     * @throws RedirectException
     */
    function force_https(
        int $duration = 31_536_000,
        ?RequestInterface $request = null,
        ?ResponseInterface $response = null
    ): void {
        $request ??= Services::request();

        if (!$request instanceof IncomingRequest) {
            return;
        }

        $response ??= Services::response();

        if ((ENVIRONMENT !== 'testing' && (is_cli() || $request->isSecure()))
            || $request->getServer('HTTPS') === 'test'
        ) {
            return; // @codeCoverageIgnore
        }

        // If the session status is active, we should regenerate
        // the session ID for safety sake.
        if (ENVIRONMENT !== 'testing' && session_status() === PHP_SESSION_ACTIVE) {
            Services::session()->regenerate(); // @codeCoverageIgnore
        }

        $uri = $request->getUri()->withScheme('https');

        // Set an HSTS header
        // $response->setHeader('Strict-Transport-Security', 'max-age=' . $duration)
        $response
            ->redirect((string) $uri)
            ->setStatusCode(307)
            ->setBody('')
            ->getCookieStore()
            ->clear();

        throw new RedirectException($response);
    }
}

if (!function_exists('_solidus')) {
    /**
     * Generates the solidus character (`/`) depending on the HTML5 compatibility flag in `Config\DocTypes`
     *
     * @param DocTypes|null $docTypesConfig New config. For testing purpose only.
     *
     * @internal
     */
    // function _solidus(?DocTypes $docTypesConfig = null): string
    function _solidus(): string
    {
        // static $docTypes = null;

        // if ($docTypesConfig !== null) {
        //     $docTypes = $docTypesConfig;
        // }

        // $docTypes ??= new DocTypes();

        // if ($docTypes->html5 ?? false) {
        //     return '';
        // }

        // return ' /';

        return '';
    }
}

if (!function_exists('db_connect')) {
    /**
     * Grabs a database connection and returns it to the user.
     *
     * This is a convenience wrapper for \Config\Database::connect()
     * and supports the same parameters. Namely:
     *
     * When passing in $db, you may pass any of the following to connect:
     * - group name
     * - existing connection instance
     * - array of database configuration values
     *
     * If $getShared === false then a new connection instance will be provided,
     * otherwise it will all calls will return the same instance.
     *
     * @param array|ConnectionInterface|string|null $db
     *
     * @return BaseConnection
     */
    function db_connect($db = null, bool $getShared = true)
    {
        $db = Database::connect($db, $getShared);

        $connection = $db->getConnection();

        if ($connection) {
            $connection->createFunction("UCASE", function ($str) {
                return $str ? mb_strtoupper($str) : "";
            });
        }

        return $db;
    }
}

if (!function_exists('app_log')) {
    function app_log(string $level, string $message, array $context = [])
    {
        $path = WRITEPATH . 'app-logs/';
        $fileExtension = 'log';
        $dateFormat = 'Y-m-d H:i:s';

        if (empty($level)) {
            $level = "app";
        }

        $userData = "user[";
        $user = auth()->user();

        if ($user) {
            $userData .= "uid=..." . substr($user->uid, -8) . ", ";
        }

        $userData .= "ip=" . request()->getIPAddress() . "]";

        $filepath = $path . $level . '-' . date('Y-m-d') . '.' . $fileExtension;

        if (is_string($message)) {
            $replace = [];

            foreach ($context as $key => $val) {
                $replace['{' . $key . '}'] = $val;
            }

            if (count($replace)) {
                $message = strtr($message, $replace);
            }
        } else {
            $message = print_r($message, true);
        }

        $msg = strtoupper($level)
            . ' - ' . date($dateFormat)
            . ' - ' . $userData
            . ' --> ' . $message . "\n";

        error_log($msg, 3, $filepath);
    }
}

if (!function_exists('log_array')) {
    function log_array(?array $array): string
    {
        if (!$array || !count($array)) {
            return "";
        }

        $separator = ", ";
        $kvSeparator = "=";

        $pairs = [];

        foreach ($array as $key => $value) {
            if ($value === null || $value === "") {
                continue;
            }

            if ($key === "description" || $key === "comments") {
                continue;
            }

            $valueType = gettype($value);

            if ($valueType === "string") {
                $len = mb_strlen($value);

                if ($len > 32) {
                    $value = trim(mb_substr($value, 0, 24)) . "...";
                }
            }
            else if ($valueType === "boolean") {
                $value = $value ? "true" : "false";
            }

            $pairs[] = gettype($key) !== "string" ? $value : $key . $kvSeparator . $value;
        }

        return implode($separator, $pairs);
    }
}

if (!function_exists('implode_kv')) {
    function implode_kv(string $separator, array $array, string $kvSeparator = "="): string
    {
        $pairs = [];

        foreach ($array as $key => $value) {
            if ($value === null) {
                continue;
            }

            $pairs[] = $key . $kvSeparator . $value;
        }

        return implode($separator, $pairs);
    }
}
