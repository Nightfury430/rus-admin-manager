<?php

declare(strict_types=1);

namespace App\Libraries;

class DBInfo
{
    public function getFields($tableName)
    {
        return array_keys($this->getFieldsInfo($tableName));
    }

    public function getFieldsInfo($tableName)
    {
        if ($tableName === "users") {
            $authTables = auth()->getProvider()->getAuthTables();

            $users = $authTables["users"];
            $groups = $authTables["groups_users"];
            $identities = $authTables["identities"];

            return [
                "id" => ["type" => "int", "column" => "{$users}.id"],
                "username" => ["type" => "string", "column" => "{$users}.username"],
                "status" => ["type" => "string", "column" => "{$users}.status"],
                "status_message" => ["type" => "string", "column" => "{$users}.status_message"],
                "active" => ["type" => "bool", "notNull" => true, "column" => "{$users}.active"],
                "last_active" => ["type" => "string", "column" => "{$users}.last_active"],
                "created_at" => ["type" => "string", "column" => "{$users}.created_at"],
                "updated_at" => ["type" => "string", "column" => "{$users}.updated_at"],
                "deleted_at" => ["type" => "string", "column" => "{$users}.deleted_at"],
                "client_active" => ["type" => "bool", "notNull" => true, "column" => "{$users}.client_active"],
                "uid" => ["type" => "uid", "notNull" => true, "column" => "{$users}.uid"],
                "group" => ["type" => "string", "notNull" => true, "column" => "{$groups}.group"],
                "secret" => ["type" => "string", "notNull" => true, "column" => "{$identities}.secret"],
            ];
        }

        return match ($tableName) {
            "clients" => [
                "id" => ["type" => "uid", "column" => "clients.id"],
                "ci_id" => ["type" => "int", "column" => "clients.ci_id"],
                "name" => ["type" => "string", "column" => "clients.name"],
                "region" => ["type" => "string", "column" => "clients.region"],
                "contact" => ["type" => "string", "column" => "clients.contact"],
                "phone" => ["type" => "string", "format" => "phone", "column" => "clients.phone"],
                "email" => ["type" => "string", "column" => "clients.email"],
                "site" => ["type" => "string", "column" => "clients.site"],
                "inn" => ["type" => "string", "column" => "clients.inn"],
                "login" => ["type" => "string", "column" => "clients.login"],
                "tariff" => ["type" => "int", "column" => "clients.tariff"],
                "last_tariff" => ["type" => "int", "column" => "clients.last_tariff"],
                "last_tariff_date" => ["type" => "date", "column" => "clients.last_tariff_date"],
                "date_created" => ["type" => "date", "column" => "clients.date_created"],
                "date_end" => ["type" => "date", "column" => "clients.date_end"],
                "deleted" => ["type" => "date", "column" => "clients.deleted"],
                "server" => ["type" => "string", "column" => "clients.server"],
                "folder" => ["type" => "string", "column" => "clients.folder"],
                "login_id" => ["type" => "string", "column" => "clients.login_id"],
                "partner" => ["type" => "int", "column" => "clients.partner"],
                "active" => ["type" => "bool", "notNull" => true, "column" => "clients.active"],
                "is_phys" => ["type" => "bool", "notNull" => true, "column" => "clients.is_phys"],
                "in_crm" => ["type" => "bool", "notNull" => true, "column" => "clients.in_crm"],
                "is_test" => ["type" => "bool", "notNull" => true, "column" => "clients.is_test"],
                "master" => ["type" => "int", "notNull" => true, "column" => "clients.master"],
                "comments" => ["type" => "text", "column" => "clients.comments"],
                "service_id" => ["type" => "uid", "notNull" => true],
                "service_client_id" => ["type" => "uid", "notNull" => true],
                "service_value" => ["type" => "string", "commonSearch" => false],
                "service_date_created" => ["type" => "date"],
                "service_date_suspended" => ["type" => "date"],
                "service_date_end" => ["type" => "date"],
            ],

            "partners" => [
                "id" => ["type" => "uid"],
                "title" => ["type" => "string", "notNull" => true],
                "description" => ["type" => "text"],
                "value" => ["type" => "int", "notNull" => true],
            ],

            "tariffs" => [
                "id" => ["type" => "uid"],
                "alias" => ["type" => "string", "notNull" => true],
                "value" => ["type" => "int", "notNull" => true],
                "title" => ["type" => "string"],
                "description" => ["type" => "text"],
                "enabled" => ["type" => "bool", "notNull" => true],
            ],

            "services" => [
                "id" => ["type" => "uid"],
                "title" => ["type" => "string"],
                "description" => ["type" => "text"],
                "alias" => ["type" => "string", "notNull" => true],
                "enabled" => ["type" => "bool", "notNull" => true],
            ],

            "client_services" => [
                "id" => ["type" => "int"],
                "client_id" => ["type" => "uid", "notNull" => true],
                "service_id" => ["type" => "uid", "notNull" => true],
                "value" => ["type" => "string"],
                "date_created" => ["type" => "date"],
                "date_suspended" => ["type" => "date"],
                "date_end" => ["type" => "date"],
            ],
        };
    }
}
