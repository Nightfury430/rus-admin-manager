<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Forge;
use CodeIgniter\Database\Migration;

class AddFieldsToShieldUsers extends Migration
{
    private array $tables;

    public function __construct(?Forge $forge = null)
    {
        parent::__construct($forge);

        /** @var \Config\Auth $authConfig */
        $authConfig   = config("Auth");
        $this->tables = $authConfig->tables;
    }

    public function up()
    {
        $this->db->disableForeignKeyChecks();

        $fields = [
            "client_active" => ["type" => "TINYINT", "constraint" => 1, "default" => 0],
            "uid" => ["type" => "VARCHAR", "constraint" => 26, "null" => false],
        ];

        $this->forge->addColumn($this->tables["users"], $fields);
        $this->forge->addKey("uid", unique: true, keyName: $this->tables["users"] . "_uid_idx");
        $this->forge->processIndexes($this->tables["users"]);

        $this->db->enableForeignKeyChecks();
    }

    public function down()
    {
        $this->db->disableForeignKeyChecks();

        $this->forge->dropKey($this->tables["users"], $this->tables["users"] . "_uid_idx");
        $this->forge->dropColumn($this->tables["users"], ["uid"]);

        $this->db->enableForeignKeyChecks();
    }
}
