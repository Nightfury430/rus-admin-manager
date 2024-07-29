<?php

declare(strict_types=1);

use CodeIgniter\Config\Factories;
use Ulid\Ulid;

if (!function_exists('lib')) {
    /**
     * Load library
     */
    function lib(string $libPath)
    {
        return Factories::libraries($libPath);
    }
}

if (!function_exists('check_folder_name')) {
    /**
     * Check that folder name contains only allowed characters
     */
    function check_folder_name(string $folderName): bool
    {
        return !(preg_match("/[^-._a-zA-Z0-9]/", $folderName) === 1);
    }
}

if (!function_exists('db_admin')) {
    /**
     * Connect to admin panel database
     *
     * @return \CodeIgniter\Database\BaseConnection
     */
    function db_admin()
    {
        /** @var \Config\Database */
        $dbConfig = config("Database");
        return db_connect($dbConfig->adminPanelGroup);
    }
}

// if (!function_exists('db_client')) {
//     /**
//      * Connect to current client database
//      */
//     function db_client()
//     {
//         auth()->user();

//         return db_connect($dbConfig->adminPanelGroup);
//     }
// }

if (!function_exists('db_client_folder')) {
    /**
     * Connect to client database by folder name
     */
    function db_client_folder(string $clientFolderName)
    {
        if (empty($clientFolderName)) {
            throw new \Exception("Client folder name is empty!");
        }

        return db_connect("client_" . $clientFolderName);
    }
}

if (!function_exists('html_mount')) {
    /**
     * Create component's mount point
     */
    function html_mount(string $name): string
    {
        return <<<END
            <div class="{$name}-mount">
                <{$name}-root></{$name}-root>
            </div>
        END;
    }
}

if (!function_exists('uid')) {
    /**
     * Generate unique id (string)
     */
    function uid(): string
    {
        return (string) Ulid::generate(lowercase: true);
    }
}

if (!function_exists('digits_hash')) {
    /**
     * Convert any string to short string of digits
     */
    function digits_hash(string $str): string
    {
        $hash = sprintf("%u", crc32($str));
        $hash = substr($hash, -5, 5);
        return str_pad($hash, 5, "0", STR_PAD_LEFT);
    }
}

if (!function_exists('response_error')) {
    /**
     * Modify response to show error
     */
    function response_error(int $httpCode)
    {
        $response = service("response")->setStatusCode($httpCode);

        if (($httpCode === 403 || $httpCode === 404) && !request()->is("json")) {
            $response->setBody(view("errors/html/error_{$httpCode}"));
        }

        return $response;
    }
}
