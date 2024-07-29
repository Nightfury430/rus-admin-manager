<?php

declare(strict_types=1);

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use CodeIgniter\Database\BaseBuilder;
use CodeIgniter\Database\BaseConnection;
use CodeIgniter\Shield\Entities\User;

class ImportOldDb extends BaseCommand
{
    protected $group = "Database";
    protected $name = "app:importold";
    protected $description = "Import old DB.";
    protected $usage = <<<'EOL'
        app:importold [options]

          Examples:
              app:importold
              app:importold --old-db-path "import/old-db.db"
              app:importold --clients-dir app-clients
              app:importold --clients-dir app-clients --only-creds
        EOL;

    protected $options = [
        "--old-db-path" => "Old DB path. (Default: app/config_db)",
        "--clients-dir" => "Directory that contains clients data folders. (Default: app-clients)",
        "--only-creds" => "Import only client's credentials from data folder.",
    ];

    public function run(array $params)
    {
        helper("app");
        helper("filesystem");
        helper("auth");

        $this->setupAuth();

        $oldDbPath = $params["old-db-path"] ?? CLI::getOption("old-db-path");
        $clientsDir = $params["clients-dir"] ?? CLI::getOption("clients-dir");
        $onlyCreds = $params["only-creds"] ?? CLI::getOption("only-creds");

        if ($oldDbPath === true) {
            CLI::error("Old DB path not set!", "light_gray", "red");
            return;
        }

        if ($clientsDir === true) {
            CLI::error("Clients data directory not set!", "light_gray", "red");
            return;
        }

        $oldDbPath = empty($oldDbPath) ? WRITEPATH . "app/config_db" : WRITEPATH . $oldDbPath;
        $clientsDir = empty($clientsDir) ? WRITEPATH . "app-clients/" : WRITEPATH . $clientsDir . "/";

        /** @var \Config\Database */
        $dbConfig = config("Database");
        $apGroup = $dbConfig->adminPanelGroup;

        $oldDbConfig = array_merge($dbConfig->default, ["database" => $oldDbPath]);

        /** @var BaseConnection */
        $srcDb = $onlyCreds ? null : db_connect($oldDbConfig);

        /** @var BaseConnection */
        $destDb = db_connect($apGroup);

        if ($onlyCreds) {
            $this->importClientsCreds($destDb, $clientsDir);
            return;
        }

        CLI::write("Run old DB import.", "yellow");
        CLI::write("Warning! Panel users will not be imported. You should create them by youself.", "yellow");
        CLI::write();

        $this->importServices($srcDb, $destDb);
        $this->importTariffs($srcDb, $destDb);
        $this->importPartners($srcDb, $destDb);
        $this->importClients($srcDb, $destDb, $clientsDir);
    }

    private function importServices(BaseConnection $srcDb, BaseConnection $destDb)
    {
        CLI::write("[Services] Import services...", "yellow");

        $servicesNames = $this->getServicesFieldNames($srcDb);
        $countAll = count($servicesNames);
        $total = 0;

        try {
            CLI::write("[Services] Number of services found: {$countAll}", "yellow");

            $builder = $destDb->table("services");

            foreach ($servicesNames as $serviceName) {
                $insertData = [
                    "id" => uid(),
                    "title" => $serviceName,
                    "description" => null,
                    "alias" => $serviceName,
                    "enabled" => 1,
                ];

                if ($builder->insert($insertData) === false) {
                    throw new \Exception("Can't insert service's data!");
                }

                $total += 1;
            }
        } catch (\Throwable $th) {
            CLI::write("[Services] Imported services: {$total}", "green");
            throw $th;
        }

        CLI::write("[Services] Imported services: {$total}", "green");
        CLI::write();
    }

    private function importTariffs(BaseConnection $srcDb, BaseConnection $destDb)
    {
        CLI::write("[Tariffs] Import tariffs...", "yellow");

        $query = $srcDb->table("Tariffs")->get();

        $buffer = [];
        $bufferSize = 20;
        $current = 0;
        $total = 0;

        try {
            if (!$query) {
                throw new \Exception("Can't run query: {$srcDb->error()}");
            }

            $countAll = $srcDb->table("Tariffs")->countAll();
            CLI::write("[Tariffs] Number of records found: {$countAll}", "yellow");

            $builder = $destDb->table("tariffs");

            while ($row = $query->getUnbufferedRow()) {
                $insertData = [
                    "id" => uid(),
                    "alias" => $row->name,
                    "value" => $row->value,
                    "title" => $row->name,
                    "description" => null,
                    "enabled" => 1,
                ];

                $this->filterInsertData($insertData);
                $buffer[] = $insertData;

                $current += 1;

                if ($current >= $bufferSize) {
                    $this->insertBatch($builder, $buffer, $bufferSize);
                    $total += $current;
                    $current = 0;
                    $buffer = [];
                }
            }

            if (!empty($buffer)) {
                $this->insertBatch($builder, $buffer, $bufferSize);
                $total += count($buffer);
            }
        } catch (\Throwable $th) {
            CLI::write("[Tariffs] Imported records: {$total}", "green");
            throw $th;
        }

        CLI::write("[Tariffs] Imported records: {$total}", "green");
        CLI::write();
    }

    private function importPartners(BaseConnection $srcDb, BaseConnection $destDb)
    {
        CLI::write("[Partners] Import partners...", "yellow");

        /** @var \App\Libraries\Table */
        $tableLib = lib("Table");

        $query = $srcDb->table("Partners")->get();

        $buffer = [];
        $bufferSize = 20;
        $current = 0;
        $total = 0;

        try {
            if (!$query) {
                throw new \Exception("Can't run query: {$srcDb->error()}");
            }

            $countAll = $srcDb->table("Partners")->countAll();
            CLI::write("[Partners] Number of records found: {$countAll}", "yellow");

            $builder = $destDb->table("partners");

            while ($row = $query->getUnbufferedRow()) {
                $insertData = [
                    "id" => uid(),
                    "title" => $row->name,
                    "description" => null,
                    "value" => $row->value,
                ];

                $this->filterInsertData($insertData);
                $insertData["value"] = $tableLib->parseIntFromString($insertData["value"]);

                $buffer[] = $insertData;

                $current += 1;

                if ($current >= $bufferSize) {
                    $this->insertBatch($builder, $buffer, $bufferSize);
                    $total += $current;
                    $current = 0;
                    $buffer = [];
                }
            }

            if (!empty($buffer)) {
                $this->insertBatch($builder, $buffer, $bufferSize);
                $total += count($buffer);
            }
        } catch (\Throwable $th) {
            CLI::write("[Partners] Imported records: {$total}", "green");
            throw $th;
        }

        CLI::write("[Partners] Imported records: {$total}", "green");
        CLI::write();
    }

    private function importClients(BaseConnection $srcDb, BaseConnection $destDb, string $clientsDir)
    {
        CLI::write("[Clients] Import clients...", "yellow");

        /** @var \App\Libraries\Table */
        $tableLib = lib("Table");

        $current = 0;
        $creds = 0;
        $errors = 0;

        try {
            $servicesNames = $this->getServicesFieldNames($srcDb);

            $servicesRows = $destDb->table("services")
                ->select("id, alias")
                ->get()
                ->getResultArray();

            $services = [];

            foreach ($servicesRows as $value) {
                if (!in_array($value["alias"], $servicesNames)) {
                    continue;
                }

                $services[$value["alias"]] = $value["id"];
            }

            $query = $srcDb->table("Clients")->get();

            if (!$query) {
                throw new \Exception("Can't run query: {$srcDb->error()}");
            }

            /** @var \App\Models\UserModel */
            $userProvider = auth()->getProvider();

            $countAll = $srcDb->table("Clients")->countAll();
            CLI::write("[Clients] Number of records found: {$countAll}", "yellow");

            CLI::write("[Clients] Import clients data...", "yellow");

            $newToOldIdsMap = [];
            $oldToNewIdsMap = [];

            $clientServicesTable = $destDb->table("client_services");
            $builder = $destDb->table("clients");

            while ($row = $query->getUnbufferedRow()) {
                $clientFolderName = trim($row->folder ?? "");
                $userCreds = [];

                if (!empty($clientFolderName) && !check_folder_name($clientFolderName)) {
                    $errors += 1;
                    CLI::write("[Clients] Folder name contains forbidden symbols: \"{$clientFolderName}\" (user id = {$row->id})", "red");
                } else {
                    $userCreds = $this->findCredentials($clientsDir, $clientFolderName);
                }

                $isCredsFound = !empty($userCreds);

                $userCreds["uid"] = uid();
                $user = new User($userCreds);

                $clientActive = $userProvider->isClientActive(["active" => $row->active]) ? 1 : 0;
                $user->fill(["client_active" => $clientActive]);

                if (!$userProvider->save($user)) {
                    throw new \Exception("Can't create user!");
                }

                $user = $userProvider->findById($userProvider->getInsertID());

                if (!$user) {
                    throw new \Exception("Can't create user!");
                }

                $userGroup = $row->master > 0 ? "cp-user" : "cp-admin";
                $user->addGroup($userGroup);

                if ($clientActive === 1) {
                    $user->activate();
                }

                if (empty($clientFolderName)) {
                    $clientFolderName = null;
                }

                $insertData = [
                    "id" => $user->uid,
                    "ci_id" => $user->id,
                    "name" => $row->name,
                    "region" => $row->region,
                    "contact" => $row->contact,
                    "phone" => $row->phone,
                    "email" => $row->email,
                    "site" => $row->site,
                    "inn" => $row->inn,
                    "login" => $row->login,
                    "tariff" => $row->tariff,
                    "last_tariff" => $row->last_tariff,
                    "last_tariff_date" => $row->last_tariff_date,
                    "date_created" => $row->date_created,
                    "date_end" => $row->date_end,
                    "deleted" => null,
                    "server" => null,
                    "folder" => $clientFolderName,
                    "login_id" => null,
                    "partner" => $row->partner,
                    "active" => $row->active,
                    "is_phys" => $row->is_phys,
                    "in_crm" => $row->in_crm,
                    "is_test" => $row->is_test,
                    "master" => $row->master,
                    "comments" => $row->comments,
                ];

                $this->filterInsertData($insertData);

                $insertData["phone"] = $tableLib->filterPhone($insertData["phone"]);

                $insertData["tariff"] = $tableLib->parseIntFromString($insertData["tariff"]);
                $insertData["last_tariff"] = $tableLib->parseIntFromString($insertData["last_tariff"]);
                $insertData["partner"] = $tableLib->parseIntFromString($insertData["partner"]);

                $insertData["last_tariff_date"] = $this->convertDate($insertData["last_tariff_date"]);
                $insertData["date_created"] = $this->convertDate($insertData["date_created"]);
                $insertData["date_end"] = $this->convertDate($insertData["date_end"]);

                if ($builder->insert($insertData) === false) {
                    throw new \Exception("Can't insert client's data!");
                }

                foreach ($services as $alias => $serviceId) {
                    $value = $row->$alias;

                    if (empty($value)) {
                        continue;
                    }

                    $csData = [
                        "client_id" => $insertData["id"],
                        "service_id" => $serviceId,
                    ];

                    if ($clientServicesTable->insert($csData) === false) {
                        throw new \Exception("Can't insert client service's data!");
                    }
                }

                $newToOldIdsMap["{$user->id}"] = $row->id;
                $oldToNewIdsMap["{$row->id}"] = $user->id;

                if ($isCredsFound) {
                    $creds += 1;
                }

                $current += 1;
            }

            CLI::write("[Clients] Update master ids...", "yellow");

            $builder = $destDb->table("clients")
                ->select("id, ci_id, master")
                ->where("master >", 0)
                ->get();

            $query = $destDb->table("clients");

            while ($row = $builder->getUnbufferedRow()) {
                if (!array_key_exists("{$row->ci_id}", $newToOldIdsMap)) {
                    continue;
                }

                if (!array_key_exists("{$row->master}", $oldToNewIdsMap)) {
                    $errors += 1;
                    CLI::write("[Clients] Can't find old master id: {$row->master}", "red");
                    continue;
                }

                $newMasterId = $oldToNewIdsMap["{$row->master}"];

                $query->set("master", $newMasterId)
                    ->where("id", $row->id)
                    ->update();
            }
        } catch (\Throwable $th) {
            $errors += 1;
            CLI::write("[Clients] Users imported: {$current}", "green");
            CLI::write("[Clients] Credentials imported: {$creds}", "green");
            CLI::write("[Clients] Errors found: {$errors}", $errors > 0 ? "red" : "green");
            CLI::write();
            throw $th;
        }

        CLI::write("[Clients] Users imported: {$current}", "green");
        CLI::write("[Clients] Credentials imported: {$creds}", "green");
        CLI::write("[Clients] Errors found: {$errors}", $errors > 0 ? "red" : "green");
        CLI::write();
    }

    private function importClientsCreds(BaseConnection $destDb, string $clientsDir)
    {
        CLI::write("[Clients] Import clients credentials...", "yellow");

        $creds = 0;
        $errors = 0;

        try {
            /** @var \App\Models\UserModel */
            $userProvider = auth()->getProvider();

            $countAll = $destDb->table("clients")->countAll();
            CLI::write("[Clients] Number of records found: {$countAll}", "yellow");

            $builder = $destDb->table("clients")
                ->select("id, ci_id, folder")
                ->where("ci_id >", 0)
                ->get();

            while ($row = $builder->getUnbufferedRow()) {
                $user = $userProvider->findById($row->ci_id);

                if (!$user) {
                    continue;
                }

                $clientFolderName = $row->folder;
                $userCreds = [];

                if (!empty($clientFolderName) && !check_folder_name($clientFolderName)) {
                    $errors += 1;
                    CLI::write("[Clients] Folder name contains forbidden symbols: \"{$clientFolderName}\" (user id = {$row->ci_id})", "red");
                } else {
                    $userCreds = $this->findCredentials($clientsDir, $clientFolderName);
                }

                if (empty($userCreds)) {
                    continue;
                }

                $user->fill($userCreds);

                if (!$userProvider->save($user)) {
                    $errors += 1;
                    CLI::write("[Clients] Can't save user credentials (user id = {$row->ci_id})", "red");
                    continue;
                }

                $creds += 1;
            }
        } catch (\Throwable $th) {
            $errors += 1;
            CLI::write("[Clients] Credentials updated: {$creds}", "green");
            CLI::write("[Clients] Errors found: {$errors}", $errors > 0 ? "red" : "green");
            CLI::write();
            throw $th;
        }

        CLI::write("[Clients] Credentials updated: {$creds}", "green");
        CLI::write("[Clients] Errors found: {$errors}", $errors > 0 ? "red" : "green");
        CLI::write();
    }

    private function filterInsertData(&$insertData)
    {
        foreach ($insertData as $key => $value) {
            if (gettype($value) === "integer") {
                continue;
            }

            if (gettype($value) === "string") {
                $value = trim($value);

                if (empty($value) || strtolower($value) === "null") {
                    $value = null;
                }

                $insertData[$key] = $value;
            }
        }
    }

    private function convertDate($date)
    {
        if (gettype($date) === "integer") {
            return $date;
        }

        if (gettype($date) !== "string") {
            return null;
        }

        if (strlen($date) === 10) {
            $parts = explode(".", $date);

            if (count($parts) === 3 && strlen($parts[2]) === 4) {
                return implode("-", array_reverse($parts)) . "T00:00:00";
            }
        }

        return $date;
    }

    private function findCredentials(string $clientsDir, ?string $clientFolderName): array
    {
        if (empty($clientFolderName)) {
            return [];
        }

        $credsPath = $clientsDir . $clientFolderName . "/config/data/usr.txt";

        if (!file_exists($credsPath)) {
            return [];
        }

        try {
            $text = file_get_contents($credsPath);
            [$login, $passwordHash] = explode(";", $text);
            $login = trim($login);
            $passwordHash = trim($passwordHash);

            if (!empty($login) && !empty($passwordHash)) {
                return [
                    "email" => $login,
                    "password_hash" => $passwordHash,
                ];
            }
        } catch (\Throwable $th) {
        }

        return [];
    }

    private function insertBatch(BaseBuilder $builder, array $buffer, int $bufferSize)
    {
        if ($builder->insertBatch($buffer, batchSize: $bufferSize) === false) {
            throw new \Exception("Can't insert data!");
        }
    }

    private function getServicesFieldNames(BaseConnection $srcDb): array
    {
        $result = [];

        $servicesCheck = [
            "add_beauty_url",
            "add_logo_full",
            "add_logo_part",
            "add_vk",
        ];

        try {
            foreach ($servicesCheck as $serviceName) {
                if (!$srcDb->fieldExists($serviceName, tableName: "Clients")) {
                    continue;
                }

                $result[] = $serviceName;
            }
        } catch (\Throwable) { }

        return $result;
    }

    private function setupAuth()
    {
        /** @var \Config\Auth $authConfig  */
        $authConfig = config("Auth");

        /** @var \Config\Database */
        $dbConfig = config("Database");

        $authConfig->DBGroup = $dbConfig->adminPanelGroup;
    }
}
