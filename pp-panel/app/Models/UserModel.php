<?php

declare(strict_types=1);

namespace App\Models;

use CodeIgniter\Shield\Entities\User;
use CodeIgniter\Shield\Models\UserModel as ShieldUserModel;

class UserModel extends ShieldUserModel
{
    protected function initialize(): void
    {
        parent::initialize();

        $this->allowedFields = [
            ...$this->allowedFields,
            'client_active',
            'uid',
        ];
    }

    public function findByUid($uid): ?User
    {
        throw new \Exception("Not implemented!");
    }

    public function createUser(string $group, array $data): ?User
    {
        if (empty($group)) {
            throw new \Exception("Creating user without a group not allowed!");
        }

        $data["uid"] = uid();

        $user = new User($data);

        if (!$this->save($user)) {
            return null;
        }

        $user = $this->findById($this->getInsertID());
        $user->addGroup($group);

        $dataString = log_array([
            "id" => $user->id,
            "group" => $group,
            ...$data,
        ]);

        app_log(LOG_DB, "User created: {0}", [$dataString]);
        app_log(LOG_SECURITY, "User created: {0}", [$dataString]);

        return $user;
    }

    public function createClient(int $userId, string $userUid, array $data)
    {
        if (empty($data)) {
            throw new \Exception("Creating client without a data not allowed!");
        }

        if (empty($userUid)) {
            throw new \Exception("Creating client without a user uid not allowed!");
        }

        $db = db_admin();

        $validated = [
            "id" => $userUid,
            "ci_id" => $userId,
        ];

        /** @var \App\Libraries\DBInfo */
        $dbInfoLib = lib("DBInfo");

        $keys = array_diff($dbInfoLib->getFields("clients"), array_keys($validated));

        foreach ($keys as $key) {
            if (array_key_exists($key, $data)) {
                $validated[$key] = $data[$key];
            }
        }

        /** @var \App\Libraries\Table */
        $tableLib = lib("Table");

        $query = $db->table("clients")->insert([...$validated, "date_created" => $tableLib->getCurrentDateString()]);

        if (!$query) {
            return false;
        }

        app_log(LOG_DB, "Client created: {0}", [log_array($validated)]);

        return $validated;
    }

    public function canEditUsers(array $userPermissions, array $idsForEditing, ?string $action = null): bool
    {
        $allowedGroups = array_keys(array_filter($userPermissions, static function ($x) {
            return $x === true;
        }));

        if (!count($allowedGroups)) {
            return false;
        }

        $disallowedGroups = array_diff($this->getAuthGroups(), $allowedGroups);

        $isNew = $action === "new";

        if (!$isNew && count($disallowedGroups)) {
            $authTables = $this->getAuthTables();

            /** @var \CodeIgniter\Database\BaseConnection */
            $db = db_admin();

            $query = $db->table($authTables["groups_users"])
                ->whereIn("user_id", $idsForEditing)
                ->whereIn("group", $disallowedGroups);

            $count = $query->countAllResults();

            if ($count > 0) {
                return false;
            }
        }

        return true;
    }

    public function isClientActive(?array $clientData): bool
    {
        return $clientData && $clientData["active"] === 1 && empty($clientData["deleted"]);
    }

    public function getAuthGroups()
    {
        /** @var \Config\AuthGroups */
        $authGroupsConfig = config("AuthGroups");
        $authGroups = array_filter(array_keys($authGroupsConfig->groups), static function ($x) {
            return $x !== "empty";
        });

        return $authGroups;
    }

    public function getAuthTables()
    {
        /** @var \Config\Auth */
        $authConfig = config("Auth");
        return $authConfig->tables;
    }
}
