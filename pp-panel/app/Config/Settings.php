<?php

declare(strict_types=1);

namespace Config;

use CodeIgniter\Settings\Config\Settings as BaseSettings;
use CodeIgniter\Settings\Handlers\ArrayHandler;
use CodeIgniter\Settings\Handlers\DatabaseHandler;

class Settings extends BaseSettings
{
    /**
     * The available handlers. The alias must
     * match a public class var here with the
     * settings array containing 'class'.
     *
     * @var string[]
     */
    public $handlers = ['array'];

    /**
     * Array handler settings.
     */
    public $array = [
        'class'     => ArrayHandler::class,
        'writeable' => true,
    ];

    /**
     * Database handler settings.
     */
    public $database = [
        'class'     => DatabaseHandler::class,
        'table'     => 'ci_settings',
        'group'     => null,
        'writeable' => true,
    ];
}
