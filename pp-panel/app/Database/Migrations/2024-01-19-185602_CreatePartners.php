<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePartners extends Migration
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
            "title" => [
                "type" => "VARCHAR",
                "constraint" => 255,
            ],
            "description" => [
                "type" => "TEXT",
                "null" => true,
            ],
            "value" => [
                "type" => "INT",
                "constraint" => 11,
            ],
        ]);

        $this->forge->addKey("id", primary: true, unique: true, keyName: "partners_id_idx");

        $this->forge->createTable("partners");

        $this->db->enableForeignKeyChecks();
    }

    public function down()
    {
        $this->db->disableForeignKeyChecks();

        $this->forge->dropTable("partners", ifExists: true);

        $this->db->enableForeignKeyChecks();
    }
}
