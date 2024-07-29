<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateServices extends Migration
{
    public function up()
    {
        $this->db->disableForeignKeyChecks();

        $varchar255 = ["type" => "VARCHAR", "constraint" => 255, "null" => true];

        $this->forge->addField([
            "id" => ["type" => "VARCHAR", "constraint" => 26, "unique" => true],
            "title" => $varchar255,
            "description" => ["type" => "TEXT", "null" => true],
            "alias" => ["type" => "VARCHAR", "constraint" => 255, "unique" => true],
            "enabled" => ["type" => "TINYINT", "constraint" => 1, "default" => 1],
        ]);

        $this->forge->addKey("id", primary: true, unique: true, keyName: "services_id_idx");
        $this->forge->createTable("services");

        $this->forge->addField([
            "id" => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            "client_id" => ["type" => "VARCHAR", "constraint" => 26],
            "service_id" => ["type" => "VARCHAR", "constraint" => 26],
            "value" => $varchar255,
            "date_created" => $varchar255,
            "date_suspended" => $varchar255,
            "date_end" => $varchar255,
        ]);

        $this->forge->addKey("id", primary: true, unique: true, keyName: "client_services_id_idx");
        $this->forge->createTable("client_services");

        $this->db->enableForeignKeyChecks();
    }

    public function down()
    {
        $this->db->disableForeignKeyChecks();

        $this->forge->dropTable("client_services", ifExists: true);
        $this->forge->dropTable("services", ifExists: true);

        $this->db->enableForeignKeyChecks();
    }
}
