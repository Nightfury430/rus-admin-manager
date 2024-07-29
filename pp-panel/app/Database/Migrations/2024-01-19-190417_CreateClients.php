<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateClients extends Migration
{
    public function up()
    {
        $this->db->disableForeignKeyChecks();

        $varchar255 = ["type" => "VARCHAR", "constraint" => 255, "null" => true];

        $this->forge->addField([
            "id" => ["type" => "VARCHAR", "constraint" => 26, "unique" => true],
            "ci_id" => ["type" => "INT", "constraint" => 11, "null" => true],
            "name" => $varchar255,
            "region" => $varchar255,
            "contact" => $varchar255,
            "phone" => $varchar255,
            "email" => $varchar255,
            "site" => $varchar255,
            "inn" => $varchar255,
            "login" => $varchar255,
            "tariff" => ["type" => "INT", "constraint" => 11, "null" => true],
            "last_tariff" => ["type" => "INT", "constraint" => 11, "null" => true],
            "last_tariff_date" => $varchar255,
            "date_created" => $varchar255,
            "date_end" => $varchar255,
            "deleted" => $varchar255,
            "server" => $varchar255,
            "folder" => $varchar255,
            "login_id" => $varchar255,
            "partner" => ["type" => "INT", "constraint" => 11, "null" => true],
            "active" => ["type" => "TINYINT", "constraint" => 1, "default" => 0],
            "is_phys" => ["type" => "TINYINT", "constraint" => 1, "default" => 0],
            "in_crm" => ["type" => "TINYINT", "constraint" => 1, "default" => 0],
            "is_test" => ["type" => "TINYINT", "constraint" => 1, "default" => 0],
            "master" => ["type" => "INT", "constraint" => 11, "default" => 0],
            "comments" => ["type" => "TEXT", "null" => true],
        ]);

        $this->forge->addKey("id", primary: true, unique: true, keyName: "clients_id_idx");
        $this->forge->addKey("ci_id", keyName: "clients_ci_id_idx");

        $this->forge->createTable("clients");

        $this->db->enableForeignKeyChecks();
    }

    public function down()
    {
        $this->db->disableForeignKeyChecks();

        $this->forge->dropTable("clients", ifExists: true);

        $this->db->enableForeignKeyChecks();
    }
}
