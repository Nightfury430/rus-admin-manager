<?php

declare(strict_types=1);

namespace Config;

use CodeIgniter\Config\BaseService;
use App\Libraries\CodeIgniter;
use App\Libraries\GlobalState;
use App\Libraries\Passwords;
use Config\View as ViewConfig;

/**
 * Services Configuration file.
 *
 * Services are simply other classes/libraries that the system uses
 * to do its job. This is used by CodeIgniter to allow the core of the
 * framework to be swapped out easily without affecting the usage within
 * the rest of your application.
 *
 * This file holds any application-specific services, or service overrides
 * that you might need. An example has been included with the general
 * method format you should use for your service methods. For more examples,
 * see the core Services file at system/Config/Services.php.
 */
class Services extends BaseService
{
    public static function globalstate(bool $getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('globalstate');
        }

        return new GlobalState();
    }

    public static function codeigniter(?App $config = null, bool $getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('codeigniter', $config);
        }

        $config ??= config(App::class);

        return new CodeIgniter($config);
    }

    public static function parser(?string $viewPath = null, ?ViewConfig $config = null, bool $getShared = true)
    {
        // if ($getShared) {
        //     return static::getSharedInstance('parser', $viewPath, $config);
        // }

        // $viewPath = $viewPath ?: (new Paths())->viewDirectory;
        // $config ??= config(ViewConfig::class);

        // return new Parser($config, $viewPath, AppServices::locator(), CI_DEBUG, AppServices::logger());

        throw new \Exception("Parser service disabled!");
    }

    public static function passwords(bool $getShared = true): Passwords
    {
        if ($getShared) {
            return self::getSharedInstance('passwords');
        }

        return new Passwords(config('Auth'));
    }
}
