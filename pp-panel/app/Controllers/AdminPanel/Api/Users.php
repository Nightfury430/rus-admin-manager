<?php

declare(strict_types=1);

namespace App\Controllers\AdminPanel\Api;

use App\Controllers\AdminPanel\JsonApiController;
use CodeIgniter\Shield\Validation\ValidationRules;

class Users extends JsonApiController
{
    public function postGetUsers()
    {
        if (!auth()->user()->can("ap-user.read")) {
            return $this->failForbidden();
        }

        $authTables = auth()->getProvider()->getAuthTables();

        $ctx = [
            "queryBuilder" => function ($db, $input) use ($authTables) {
                return $db->table($authTables["groups_users"])
                    ->select("{$authTables["users"]}.*")
                    ->select("{$authTables["identities"]}.secret")
                    ->whereIn("{$authTables["groups_users"]}.group", ["ap-admin", "ap-user"])
                    ->where("{$authTables["identities"]}.type", "email_password")
                    ->join($authTables["users"], "{$authTables["users"]}.id = {$authTables["groups_users"]}.user_id", "left")
                    ->join($authTables["identities"], "{$authTables["identities"]}.user_id = {$authTables["users"]}.id", "left");
            },
        ];

        return $this->tableApi("get", "users", $ctx);
    }

    public function postSaveUsers()
    {
        $input = $this->input() ?? [];
        $inputRows = !empty($input["rows"]) ? $input["rows"] : [];

        /** @var \App\Libraries\Table */
        $tableLib = lib("Table");

        $checkInput = $tableLib->checkInputForSave($input);

        if (!$checkInput["success"]) {
            return $this->fail($checkInput["message"]);
        }

        $newCount = 0;
        $editCount = 0;
        $emptyEmailCount = 0;

        foreach ($inputRows as $row) {
            if (empty($row["id"])) {
                $newCount += 1;
            } else {
                $editCount += 1;
            }

            if (empty($row["secret"])) {
                $emptyEmailCount += 1;
            }
        }

        if ($newCount > 0 && $editCount > 0) {
            return $this->fail("Can't create and edit in single operation!");
        }

        $isNew = $newCount > 0;

        if ($isNew && $emptyEmailCount > 0) {
            return $this->fail("User's email can't be empty!");
        }

        /** @var \App\Models\UserModel */
        $users = auth()->getProvider();

        $user = auth()->user();

        $permissions = [
            "ap-user" => $user->can($isNew ? "ap-user.add" : "ap-user.edit"),
            "ap-admin" => $user->can("ap-admin.manage"),
            "cp-user" => $user->can($isNew ? "cp-user.add" : "cp-user.edit"),
            "cp-admin" => $user->can("cp-admin.manage"),
        ];

        $ids = [];

        foreach ($inputRows as $row) {
            if (!empty($row["id"])) {
                $ids[] = $row["id"];
            }
        }

        if (!$users->canEditUsers($permissions, $ids, action: $isNew ? "new" : "edit")) {
            return $this->failForbidden();
        }

        unset($ids);

        $defaultGroup = "ap-user";

        if ($isNew && empty($permissions[$defaultGroup])) {
            return $this->failForbidden();
        }

        $fieldsInfo = [
            "username" => ["type" => "string"],
            "active" => ["type" => "bool", "notNull" => true],
            "secret" => ["type" => "string", "notNull" => true],
        ];

        $timeZone = new \DateTimeZone("UTC");
        $timeZoneUTC = new \DateTimeZone("UTC");

        try {
            $timeZone = new \DateTimeZone(empty($input["dateTimeZone"]) ? "UTC" : $input["dateTimeZone"]);
        } catch (\Throwable $th) {
        }

        foreach ($inputRows as $row) {
            $data = [];

            $data = $tableLib->filterRowOnSave($row, array_keys($fieldsInfo), $fieldsInfo, $timeZone, $timeZoneUTC);

            if (isset($data["secret"])) {
                if ($isNew && !empty($data["secret"])) {
                    $data["email"] = $data["secret"];
                }

                unset($data["secret"]);
            }

            if (isset($data["username"]) && $data["username"] === "") {
                $data["username"] = null;
            }

            if (count($data) === 0) {
                return $this->fail("Nothing to save!");
            }

            if ($isNew) {
                $user = $users->createUser($defaultGroup, $data);

                if (!$user) {
                    return $this->fail("Can't save user!");
                }

                continue;
            }

            $data["id"] = $row["id"];

            if (!$users->save($data)) {
                return $this->fail("Can't save user!");
            }

            $dataString = log_array($data);

            app_log(LOG_DB, "User saved: {0}", [$dataString]);
            app_log(LOG_SECURITY, "User saved: {0}", [$dataString]);
        }

        return $this->success();
    }

    public function postDeleteUsers()
    {
        $input = $this->input();
        $ids = $input["ids"] ?? [];

        /** @var \App\Libraries\Table */
        $tableLib = lib("Table");

        $checkInput = $tableLib->checkInputForDelete($input);

        if (!$checkInput["success"]) {
            return $this->fail($checkInput["message"]);
        }

        $user = auth()->user();
        $users = auth()->getProvider();

        $permissions = [
            "ap-user" => $user->can("ap-user.remove"),
            "ap-admin" => $user->can("ap-admin.manage"),
            "cp-user" => $user->can("cp-user.remove"),
            "cp-admin" => $user->can("cp-admin.manage"),
        ];

        if (!$users->canEditUsers($permissions, $ids)) {
            return $this->failForbidden();
        }

        if (!$users->delete($ids)) {
            return $this->fail("Can't delete records!");
        }

        $idsString = log_array($ids);

        app_log(LOG_DB, "Users deleted: ids=[{0}]", [$idsString]);
        app_log(LOG_SECURITY, "Users deleted: ids=[{0}]", [$idsString]);

        return $this->success();
    }

    public function postCreateSuperUser()
    {
        if (file_exists(WRITEPATH . "app/su_created")) {
            return $this->fail("User creation not available.");
        }

        /** @var \Config\Database */
        $dbConfig = config("Database");

        /** @var \App\Models\UserModel */
        $users = auth()->getProvider();

        $authTables = $users->getAuthTables();

        $db = db_admin();

        $query = $db->table($authTables["groups_users"])
            ->select("{$authTables["users"]}.*")
            ->whereIn("{$authTables["groups_users"]}.group", ["ap-admin"])
            ->join($authTables["users"], "{$authTables["users"]}.id = {$authTables["groups_users"]}.user_id", "left")
            ->limit(1);

        if ($query->countAllResults() !== 0) {
            return $this->fail("User creation not available.");
        }

        $inputData = $this->input();

        $rules = new ValidationRules();
        $regRules = $rules->getRegistrationRules();
        unset($regRules["username"]);
        unset($regRules["password_confirm"]);

        if (!$this->validateData($inputData, $regRules, [], $dbConfig->adminPanelGroup)) {
            return $this->fail([
                "validation" => array_values($this->validator->getErrors() ?? [])
            ]);
        }

        $validated = $this->validator->getValidated();

        $user = $users->createUser("ap-admin", [
            "email" => $validated["email"],
            "password" => $validated["password"],
            "client_active" => 1,
            "active" => 1,
        ]);

        if (!$user) {
            return $this->fail("Can't create user!");
        }

        touch(WRITEPATH . "app/su_created");

        app_log(LOG_SECURITY, "Super user created: id: {0}, email: {1}", [$user->id, $user->email]);

        return $this->success();
    }
}
