<?php

declare(strict_types=1);

namespace App\Controllers\AdminPanel\Api;

use App\Controllers\AdminPanel\JsonApiController;
use App\Libraries\ClientFolder;
use App\Libraries\Mail;

class Clients extends JsonApiController
{
    public function postGetTableData()
    {
        if (!auth()->user()->can("ap-client.read")) {
            return $this->failForbidden();
        }

        /** @var \App\Libraries\Table */
        $tableLib = lib("Table");

        $db = db_admin();
        $input = $this->input() ?? [];
        $input["pager"] = false;

        if (empty($input["tables"]) || gettype($input["tables"]) !== "array") {
            return $this->fail("No table names provided!");
        }

        $allowedTables = ["partners", "tariffs", "services"];

        $tables = array_intersect($input["tables"], $allowedTables);

        $result = [];

        foreach ($tables as $table) {
            $result[$table] = $tableLib->handleGetRows($db, $table, $input);
        }

        return $this->success($result);
    }

    public function postGetClients()
    {
        if (!auth()->user()->can("ap-client.read")) {
            return $this->failForbidden();
        }

        $ctx = [
            "queryBuilder" => function ($db, $input) {
                $isMaster = true;
                $includeServices = false;

                if (!empty($input["filters"]["fields"])) {
                    $filterFields = $input["filters"]["fields"];

                    $isMaster = empty($filterFields["master"]["value"]);

                    $includeServices = !empty($filterFields["service_id"]["value"])
                        || !empty($filterFields["service_client_id"]["value"])
                        || !empty($filterFields["service_value"]["value"])
                        || !empty($filterFields["service_date_created"]["value"])
                        || !empty($filterFields["service_date_suspended"]["value"])
                        || !empty($filterFields["service_date_end"]["value"]);
                }

                $query = $db->table("clients");

                if ($includeServices) {
                    $query = $query->select("clients.*")
                        ->select("client_services.service_id AS service_id")
                        ->select("client_services.client_id AS service_client_id")
                        ->select("client_services.value AS service_value")
                        ->select("client_services.date_created AS service_date_created")
                        ->select("client_services.date_suspended AS service_date_suspended")
                        ->select("client_services.date_end AS service_date_end")
                        ->join("client_services", "client_services.client_id = clients.id", "left")
                        ->groupBy("client_services.client_id");
                }

                if ($isMaster) {
                    $query = $query->where("master", 0);
                }

                return $query;
            },
        ];

        return $this->tableApi("get", "clients", $ctx);
    }

    public function postSaveClients()
    {
        $input = $this->input() ?? [];
        $inputRows = !empty($input["rows"]) ? $input["rows"] : [];

        $newCount = 0;
        $editCount = 0;

        foreach ($inputRows as $row) {
            if (empty($row["id"])) {
                $newCount += 1;
            } else {
                $editCount += 1;
            }
        }

        if ($newCount > 0 && $editCount > 0) {
            return $this->fail("Can't create and edit in single operation!");
        }

        if ($newCount > 0 && !auth()->user()->can("ap-client.add")) {
            return $this->failForbidden();
        }

        if ($editCount > 0 && !auth()->user()->can("ap-client.edit")) {
            return $this->failForbidden();
        }

        $ctx = ["onSaveCallback" => static function ($params) {
            ["row" => $row, "rowFiltered" => $data, "query" => $query] = $params;

            $isNew = empty($row["id"]);
            $isSub = !empty($row["master"]);

            if (count($data) === 0) {
                return "Nothing to save!";
            }

            if (!empty($data["login"])) {
                $data["login"] = strtolower($data["login"]);
            }

            /** @var \Config\App */
            $appConfig = config("App");

            /** @var \App\Models\UserModel */
            $users = auth()->getProvider();

            if (!$isNew) {
                if (!$query->set($data)->where("id", $row["id"])->update()) {
                    return "Error when saving records!";
                }

                app_log(LOG_DB, "Client saved: {0}", [log_array([...$data, "id" => $row["id"]])]);

                $queryResult = $query->select("ci_id, active, deleted")
                    ->select("login, folder, tariff, date_end, partner, master")
                    ->where("id", $row["id"])
                    ->limit(1)
                    ->get()->getResultArray() ?? [];

                if (count($queryResult) && isset($queryResult[0]["ci_id"])) {
                    $client = $queryResult[0];

                    if (gettype($client["ci_id"]) === "integer") {
                        $db = db_admin();

                        $clientActive = $client["active"] === 1 && empty($client["deleted"]) ? 1 : 0;

                        $authTables = $users->getAuthTables();

                        if (!$db->table($authTables["users"])
                            ->set(["client_active" => $clientActive])
                            ->where("id", $client["ci_id"])
                            ->update()) {
                            return "Error when saving records!";
                        }

                        app_log(LOG_DB, "User 'client_active' updated: {0}", [log_array([
                            "id" => $client["ci_id"],
                            "client_active" => $clientActive,
                        ])]);
                    }

                    if ($appConfig->clientFolderSaving) {
                        (new ClientFolder())->save($client, $isNew);
                    }
                }

                return false;
            }

            $masterFolder = "";

            if ($isSub) {
                $master = $query->select("folder")
                    ->where("ci_id", $row["master"])
                    ->limit(1)
                    ->get()->getResultArray();

                if (!empty($master[0]["folder"])) {
                    $masterFolder = $master[0]["folder"];
                }
            }

            $clientActive = $data["active"] === 1 && empty($data["deleted"]) ? 1 : 0;
            $userData = ["active" => 1, "client_active" => $clientActive];

            if (!empty($data["email"])) {
                $userData["email"] = $data["email"];
            }

            $user = $users->createUser("cp-admin", $userData);

            if (!$user) {
                return "Can't create user!";
            }

            if (empty($data["folder"])) {
                $pathStart = $isSub ? $masterFolder : $user->id . substr((string)time(), -5);
                $data["folder"] = $pathStart . "-" . substr($user->uid, -4);
            }

            $client = $users->createClient($user->id, $user->uid, $data);

            if (!$client) {
                return "Can't create client!";
            }

            if ($appConfig->clientFolderSaving) {
                (new ClientFolder())->save([...$data, "masterFolder" => $masterFolder], $isNew);
            }

            return false;
        }];

        return $this->tableApi("save", "clients", $ctx);
    }

    public function postDeleteClients()
    {
        $canDeleteClient = auth()->user()->can("ap-client.remove");

        if (!$canDeleteClient) {
            return $this->failForbidden();
        }

        $input = $this->input();
        $ids = $input["ids"] ?? [];

        /** @var \App\Libraries\Table */
        $tableLib = lib("Table");

        $checkInput = $tableLib->checkInputForDelete($input);

        if (!$checkInput["success"]) {
            return $this->fail($checkInput["message"]);
        }

        $db = db_admin();

        $ciIds = $db->table("clients")
            ->select("ci_id")
            ->whereIn("id", $ids)
            ->get()->getResultArray();

        $ciIds = $this->takeColumn($ciIds, "ci_id");

        if (count($ciIds) > 0) {
            $subs = $db->table("clients")
                ->select("id")
                ->select("ci_id")
                ->whereIn("master", $ciIds)
                ->where("master !=", 0)
                ->get()->getResultArray();

            $ids = array_merge($ids, $this->takeColumn($subs, "id"));
            $ciIds = array_merge($ciIds, $this->takeColumn($subs, "ci_id"));

            $users = auth()->getProvider();

            $permissions = [
                "cp-admin" => $canDeleteClient,
                "cp-user" => $canDeleteClient,
            ];

            if (!$users->canEditUsers($permissions, $ciIds)) {
                return $this->failForbidden();
            }

            $authTables = $users->getAuthTables();

            if (!$db->table($authTables["users"])->set(["client_active" => 0])->whereIn("id", $ciIds)->update()) {
                return $this->fail("Error when updating users records!");
            }

            app_log(LOG_DB, "Users 'client_active' updated: client_active={0}, ids=[{1}]", [0, log_array($ciIds)]);
        }

        if (!$db->table("clients")
            ->set("deleted", $tableLib->getCurrentDateString())
            ->whereIn("id", $ids)
            ->where("deleted", null)
            ->update()) {
            return $this->fail("Can't delete clients!");
        }

        app_log(LOG_DB, "Clients deleted: ids=[{0}]", [log_array($ids)]);

        return $this->success();
    }

    public function postGetClientServices()
    {
        if (!auth()->user()->can("ap-client.read")) {
            return $this->failForbidden();
        }

        return $this->tableApi("get", "client_services");
    }

    public function postGetPartners()
    {
        if (!auth()->user()->can("ap-client.read")) {
            return $this->failForbidden();
        }

        return $this->tableApi("get", "partners");
    }

    public function postSavePartners()
    {
        if (!auth()->user()->can("ap-client.edit")) {
            return $this->failForbidden();
        }

        $ctx = ["logger" => static function ($data) {
            app_log(LOG_DB, "Partner saved: {0}", [log_array($data)]);
        }];

        return $this->tableApi("save", "partners", $ctx);
    }

    public function postDeletePartners()
    {
        if (!auth()->user()->can("ap-client.remove")) {
            return $this->failForbidden();
        }

        $ctx = ["logger" => static function ($data) {
            app_log(LOG_DB, "Partners deleted: ids=[{0}]", [log_array($data)]);
        }];

        return $this->tableApi("delete", "partners", $ctx);
    }

    public function postGetTariffs()
    {
        if (!auth()->user()->can("ap-client.read")) {
            return $this->failForbidden();
        }

        return $this->tableApi("get", "tariffs");
    }

    public function postSaveTariffs()
    {
        if (!auth()->user()->can("ap-client.edit")) {
            return $this->failForbidden();
        }

        $ctx = ["logger" => static function ($data) {
            app_log(LOG_DB, "Tariff saved: {0}", [log_array($data)]);
        }];

        return $this->tableApi("save", "tariffs", $ctx);
    }

    public function postDeleteTariffs()
    {
        if (!auth()->user()->can("ap-client.remove")) {
            return $this->failForbidden();
        }

        $ctx = ["logger" => static function ($data) {
            app_log(LOG_DB, "Tariffs deleted: ids=[{0}]", [log_array($data)]);
        }];

        return $this->tableApi("delete", "tariffs", $ctx);
    }

    public function postGetServices()
    {
        if (!auth()->user()->can("ap-client.read")) {
            return $this->failForbidden();
        }

        return $this->tableApi("get", "services");
    }

    public function postSaveServices()
    {
        if (!auth()->user()->can("ap-client.edit")) {
            return $this->failForbidden();
        }

        $ctx = ["logger" => static function ($data) {
            app_log(LOG_DB, "Service saved: {0}", [log_array($data)]);
        }];

        return $this->tableApi("save", "services", $ctx);
    }

    public function postDeleteServices()
    {
        if (!auth()->user()->can("ap-client.remove")) {
            return $this->failForbidden();
        }

        $ctx = ["logger" => static function ($data) {
            app_log(LOG_DB, "Services deleted: ids=[{0}]", [log_array($data)]);
        }];

        return $this->tableApi("delete", "services", $ctx);
    }

    public function postResetPassword()
    {
        if (!auth()->user()->can("ap-client.edit")) {
            return $this->failForbidden();
        }

        /** @var \Config\App */
        $appConfig = config("App");

        if (!$appConfig->clientFolderSaving) {
            return $this->success();
        }

        $input = $this->input() ?? [];
        $id = $input["id"] ?? null;

        if (!$id) {
            return $this->fail("Client id not set!");
        }

        $queryResult = db_admin()->table("clients")
            ->select("folder")
            ->where("id", $id)
            ->get()
            ->getResultArray();

        if (empty($queryResult[0])) {
            return $this->fail("Client not found!");
        }

        $data = $queryResult[0];
        $folder = $data["folder"] ?? null;

        if (!$folder) {
            return $this->fail("Client folder name is empty!");
        }

        (new ClientFolder())->resetPassword($folder);

        return $this->success();
    }

    public function postSendEmail()
    {
        if (!auth()->user()->can("ap-client.edit")) {
            return $this->failForbidden();
        }

        $input = $this->input() ?? [];
        $type = $input["type"] ?? null;

        if (!$type) {
            return $this->fail("Email type not set!");
        }

        $mail = new Mail();

        if ($type === "account-created") {
            $id = $input["id"] ?? null;

            if (!$id) {
                return $this->fail("Client id not set!");
            }

            $queryResult = db_admin()->table("clients")
                ->select("login, tariff, date_end, folder, master")
                ->where("id", $id)
                ->get()
                ->getResultArray();

            if (empty($queryResult[0])) {
                return $this->fail("Client not found!");
            }

            $data = $queryResult[0];
            $login = $data["login"] ?? null;

            if (!$mail->checkEmail($login)) {
                return $this->fail("Client email is not valid!");
            }

            if (empty($data["folder"])) {
                return $this->fail("Client folder name is empty!");
            }

            if (isset($data["date_end"])) {
                $data["date_end"] = (new ClientFolder())->convertDate($data["date_end"]);
            }

            $success = $mail->sendCreateEmail($data);

            if (!$success) {
                return $this->fail("Email not sent!");
            }

            return $this->success();
        }

        return $this->fail("Unknown email type!");
    }

    private function takeColumn($src, $column)
    {
        return array_filter(array_column($src, $column), static function ($x) {
            return !empty($x);
        });
    }
}
