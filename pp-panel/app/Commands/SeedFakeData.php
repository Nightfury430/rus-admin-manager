<?php

declare(strict_types=1);

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use CodeIgniter\Database\BaseConnection;

class SeedFakeData extends BaseCommand
{
    protected $group = "Database";
    protected $name = "app:seedfakedata";
    protected $description = "Seed main DB with fake data.";
    protected $usage = <<<'EOL'
        app:seedfakedata
        EOL;

    public function run(array $params)
    {
        helper("app");

        /** @var \Config\Database */
        $dbConfig = config("Database");
        $apGroup = $dbConfig->adminPanelGroup;

        /** @var BaseConnection */
        $db = db_connect($apGroup);

        CLI::write("Run DB seeding with fake data.", "yellow");

        $this->SeedPartners($db);
        $this->SeedTariffs($db);
        $this->SeedClients($db);

        CLI::write("DB seeding finished.", "green");
    }

    private function SeedClients(BaseConnection $db)
    {
        CLI::write("Seed clients...", "yellow");

        /** @var \App\Libraries\Table */
        $tableLib = lib("Table");

        $partnersIds = $db->table("partners")->select("value")->get()->getResultArray() ?? [];
        $tariffsIds = $db->table("tariffs")->select("value")->get()->getResultArray() ?? [];
        $isPartners = count($partnersIds) > 0;
        $isTariffs = count($tariffsIds) > 0;

        if (count($partnersIds)) {
            $partnersIds = array_map(static function ($x) {
                return $x["value"];
            }, $partnersIds);
        }

        if (count($tariffsIds)) {
            $tariffsIds = array_map(static function ($x) {
                return $x["value"];
            }, $tariffsIds);
        }

        $fields = [
            "id" => null,
            "name" => null,
            "region" => null,
            "phone" => null,
            "email" => null,
            "site" => null,
            "tariff" => null,
            "date_created" => null,
            "partner" => null,
            "is_phys" => 0,
            "in_crm" => 0,
            "is_test" => 0,
            "comments" => null,
        ];

        $tableName = "clients";

        $dataPath = APPPATH . "Commands/fake-{$tableName}.json";
        $chunkSize = 50;

        $builder = $db->table($tableName);

        $data = json_decode(file_get_contents($dataPath), associative: true);

        foreach ($data as $key => $row) {
            $data[$key] = array_merge($fields, $row);

            $phone = $tableLib->filterPhone($data[$key]["phone"]);
            $data[$key]["phone"] = $phone && strlen($phone) === 10 ? "+7{$phone}" : $phone;

            $hasPartner = rand(0, 100) < 10;
            $hasTariff = rand(0, 100) < 30;

            if ($isPartners && $hasPartner) {
                $data[$key]["partner"] = $partnersIds[array_rand($partnersIds)];
            }

            if ($isTariffs && $hasTariff) {
                $data[$key]["tariff"] = $tariffsIds[array_rand($tariffsIds)];
            }
        }

        $chunks = array_chunk($data, $chunkSize);
        unset($data);

        foreach ($chunks as $chunk) {
            if ($builder->insertBatch($chunk, batchSize: $chunkSize) === false) {
                throw new \Exception("Can't insert data!");
            }
        }
    }

    private function SeedPartners(BaseConnection $db)
    {
        CLI::write("Seed partners...", "yellow");

        $fields = [
            "id" => null,
            "title" => null,
            "description" => null,
            "value" => 0,
        ];

        $this->SeedTable($db, "partners", $fields);
    }

    private function SeedTariffs(BaseConnection $db)
    {
        CLI::write("Seed tariffs...", "yellow");

        $fields = [
            "id" => null,
            "alias" => null,
            "value" => 0,
            "title" => null,
            "description" => null,
            "enabled" => 1,
        ];

        $this->SeedTable($db, "tariffs", $fields);
    }

    private function SeedTable(BaseConnection $db, string $tableName, array $fields)
    {
        $dataPath = APPPATH . "Commands/fake-{$tableName}.json";
        $chunkSize = 50;

        $builder = $db->table($tableName);

        $data = json_decode(file_get_contents($dataPath), associative: true);

        foreach ($data as $key => $row) {
            $data[$key] = array_merge($fields, $row);
        }

        $chunks = array_chunk($data, $chunkSize);
        unset($data);

        foreach ($chunks as $chunk) {
            if ($builder->insertBatch($chunk, batchSize: $chunkSize) === false) {
                throw new \Exception("Can't insert data!");
            }
        }
    }
}
