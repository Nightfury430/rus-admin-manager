<?php

declare(strict_types=1);

/**
 * This file is part of CodeIgniter Shield.
 *
 * (c) CodeIgniter Foundation <admin@codeigniter.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Config;

use CodeIgniter\Shield\Config\AuthRoutes as ShieldAuthRoutes;

class AuthRoutes extends ShieldAuthRoutes
{
    public array $routes = [
        // 'login' => [
        //     [
        //         'get',
        //         'login',
        //         'LoginController::loginView',
        //         'login', // Route name
        //     ],
        //     [
        //         'post',
        //         'login',
        //         'LoginController::loginAction',
        //     ],
        // ],
        // 'logout' => [
        //     [
        //         'get',
        //         'logout',
        //         'LoginController::logoutAction',
        //         'logout', // Route name
        //     ],
        // ],
    ];
}
