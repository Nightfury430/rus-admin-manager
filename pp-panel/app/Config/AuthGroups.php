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

use CodeIgniter\Shield\Config\AuthGroups as ShieldAuthGroups;

class AuthGroups extends ShieldAuthGroups
{
    /**
     * --------------------------------------------------------------------
     * Default Group
     * --------------------------------------------------------------------
     * The group that a newly registered user is added to.
     */
    public string $defaultGroup = 'empty';

    /**
     * --------------------------------------------------------------------
     * Groups
     * --------------------------------------------------------------------
     * An associative array of the available groups in the system, where the keys
     * are the group names and the values are arrays of the group info.
     *
     * Whatever value you assign as the key will be used to refer to the group
     * when using functions such as:
     *      $user->addGroup('superadmin');
     *
     * @var array<string, array<string, string>>
     *
     * @see https://codeigniter4.github.io/shield/quick_start_guide/using_authorization/#change-available-groups for more info
     */
    public array $groups = [
        'ap-admin' => [
            'title' => 'Super Admin',
            'description' => 'Complete control of the site (admin panel and client panel).',
        ],
        'ap-user' => [
            'title' => 'User (admin panel)',
            'description' => 'User of admin panel. Can\'t see or edit accounts of admin panel.',
        ],
        'cp-admin' => [
            'title' => 'Admin (client panel)',
            'description' => 'Administrators of client panel with full access.',
        ],
        'cp-user' => [
            'title' => 'User (client panel)',
            'description' => 'General users of client panel. Only can see basic sections.',
        ],
        'empty' => [
            'title' => 'User without permissions',
            'description' => 'Default group without permissions.',
        ],
    ];

    /**
     * --------------------------------------------------------------------
     * Permissions
     * --------------------------------------------------------------------
     * The available permissions in the system.
     *
     * If a permission is not listed here it cannot be used.
     */
    public array $permissions = [
        'ap.read' => 'Readonly access to basic sections of admin panel',
        'ap.edit' => 'Access to edit basic sections of admin panel',

        'ap-all.read' => 'Readonly access to all sections of admin panel (but not users)',
        'ap-all.edit' => 'Access to edit all sections of admin panel (but not users)',

        'ap-user.read' => 'Access to see all users of admin panel',
        'ap-user.edit' => 'Access to edit users of admin panel',
        'ap-user.add' => 'Access to add users of admin panel',
        'ap-user.remove' => 'Access to remove users of admin panel',

        'ap-admin.manage' => 'Access to add, remove and edit superadmins of admin panel',

        'ap-client.read' => 'Access to see all clients',
        'ap-client.edit' => 'Access to edit clients',
        'ap-client.add' => 'Access to add clients',
        'ap-client.remove' => 'Access to remove clients',

        'cp.read' => 'Readonly access to basic sections of client panel',
        'cp.edit' => 'Access to edit basic sections of client panel',

        'cp-all.read' => 'Readonly access to all sections of client panel (but not users)',
        'cp-all.edit' => 'Access to edit all sections of client panel (but not users)',

        'cp-user.read' => 'Access to see all users of client panel',
        'cp-user.edit' => 'Access to edit users of client panel',
        'cp-user.add' => 'Access to add users of client panel',
        'cp-user.remove' => 'Access to remove users of client panel',

        'cp-admin.manage' => 'Access to add, remove and edit admins of client panel',
    ];

    /**
     * --------------------------------------------------------------------
     * Permissions Matrix
     * --------------------------------------------------------------------
     * Maps permissions to groups.
     *
     * This defines group-level permissions.
     */
    public array $matrix = [
        'ap-admin' => [
            'ap.*',
            'ap-all.*',
            'ap-user.*',
            'ap-admin.*',
            'ap-client.*',
            'cp-all.*',
            'cp-user.*',
            'cp-admin.*',
        ],
        'ap-user' => [
            'ap.read',
            'ap.edit',
            'ap-client.read',
            'ap-client.edit',
            'ap-client.add',
        ],
        'cp-admin' => [
            'cp.*',
            'cp-all.*',
            'cp-user.*',
            'cp-admin.*',
        ],
        'cp-user' => [
            'cp.read',
        ],
        'empty' => [],
    ];
}
