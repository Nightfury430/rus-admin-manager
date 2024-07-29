<?php

declare(strict_types=1);

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\Services;
use Throwable;

class Migrate extends BaseCommand
{
    protected $group = "Database";
    protected $name = "app:migrate";
    protected $description = "Run DB migrations.";
    protected $usage = <<<'EOL'
        app:migrate [options]

          Examples:
              app:migrate --all
              app:migrate --admin-panel
              app:migrate --clients
              app:migrate --client-folder 123-megacorp_dfg4iu1p
        EOL;

    protected $options = [
        "--all" => "Run migrations for admin panel and all clients databases.",
        "--admin-panel" => "Run migrations for admin panel database only.",
        "--clients" => "Run migrations for all clients databases.",
        "--client-folder" => "Run migrations for single client database.",
    ];

    public function run(array $params)
    {
        helper("app");
        helper("filesystem");

        $paramsCount = count($params);

        if ($paramsCount !== 1) {
            CLI::error("You must set 1 option!", "light_gray", "red");
            return;
        }

        $clientFolder = $params["client-folder"] ?? CLI::getOption("client-folder");

        if ($clientFolder === true) {
            CLI::error("Client folder name not set!", "light_gray", "red");
            return;
        }

        /** @var \Config\Database */
        $dbConfig = config("Database");
        $adminPanelGroup = $dbConfig->adminPanelGroup;

        $groups = [];
        $errors = 0;
        $current = 0;

        if ((array_key_exists("all", $params) || CLI::getOption("all"))) {
            $clientFolders = $this->getFolderNames(WRITEPATH . "app-clients", prefixResult: "client_");
            $groups = [$adminPanelGroup, ...$clientFolders];
        } else if ((array_key_exists("admin-panel", $params) || CLI::getOption("admin-panel"))) {
            $groups[] = $adminPanelGroup;
        } else if ((array_key_exists("clients", $params) || CLI::getOption("clients"))) {
            $groups = $this->getFolderNames(WRITEPATH . "app-clients", prefixResult: "client_");
        } else if (
            !empty($clientFolder)
            && is_string($clientFolder)
            && check_folder_name($clientFolder)
            && is_dir(WRITEPATH . "app-clients/" . $clientFolder)
        ) {
            $groups[] = "client_" . $clientFolder;
        }

        CLI::write("Run DB migrations.", "yellow");

        $total = count($groups);

        foreach ($groups as $group) {
            $current += 1;

            CLI::write("[{$current}/{$total}] Group: {$group}", "yellow");

            if (!$this->runForSingleDb($group)) {
                $errors += 1;
            }
        }

        CLI::write("Migrations completed: {$total} total, {$errors} with errors", "green");
        CLI::write("DB migrations finished.", "green");
    }

    protected function runForSingleDb($group): bool
    {
        $status = true;
        $db = null;

        try {
            $db = db_connect($group);
            $runner = Services::migrations(config: null, db: $db, getShared: false);
            $runner->clearCliMessages();
            $runner->setNamespace(null);

            if (!$runner->latest($group)) {
                $status = false;
                CLI::error("Error has occurred!", "light_gray", "red");
            }

            $messages = $runner->getCliMessages();

            foreach ($messages as $message) {
                CLI::write($message);
            }
        } catch (Throwable $e) {
            $status = false;
            $this->showError($e);
        }

        try {
            $db->close();
        } catch (\Throwable) {
        }

        return $status;
    }

    protected function getFolderNames(string $scanPath, string $prefixResult = ""): array
    {
        $scanPath = rtrim($scanPath, "/") . "/";

        $result = [];
        $files = scandir($scanPath);

        foreach ($files as $file) {
            if ($file === "." || $file === "..") {
                continue;
            }

            if (is_dir($scanPath . $file)) {
                array_push($result, $prefixResult . $file);
            }
        }

        return $result;
    }
}
