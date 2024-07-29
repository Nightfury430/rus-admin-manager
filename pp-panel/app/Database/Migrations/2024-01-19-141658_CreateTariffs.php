<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTariffs extends Migration
{
    public function up()
    {
        $this->db->disableForeignKeyChecks();

        $this->forge->addField([
            "id" => [
                "type" => "VARCHAR",
                "constraint" => 26,
                "unique" => true,
            ],
            "alias" => [
                "type" => "VARCHAR",
                "constraint" => 255,
                "unique" => true,
            ],
            "value" => [
                "type" => "INT",
                "constraint" => 11,
            ],
            "title" => [
                "type" => "VARCHAR",
                "constraint" => 255,
                "null" => true,
            ],
            "description" => [
                "type" => "TEXT",
                "null" => true,
            ],
            "enabled" => [
                "type" => "TINYINT",
                "constraint" => 1,
                "default" => 1
            ],
        ]);

        $this->forge->addKey("id", primary: true, unique: true, keyName: "tariffs_id_idx");

        $this->forge->createTable("tariffs");

        $this->db->enableForeignKeyChecks();
    }

    public function down()
    {
        $this->db->disableForeignKeyChecks();

        $this->forge->dropTable("tariffs", ifExists: true);

        $this->db->enableForeignKeyChecks();
    }
}
