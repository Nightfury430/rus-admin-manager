<?php

declare(strict_types=1);

namespace App\Libraries;

class ClientFolder
{
    private string $clientsFolderPath;
    private string $templateFolderPath;
    private string $templateSubFolderPath;

    public function __construct()
    {
        /** @var \Config\App */
        $appConfig = config("App");

        $this->clientsFolderPath = rtrim($appConfig->clientsFolderPath, "/\\") . "/";
        $this->templateFolderPath = rtrim($appConfig->templateFolderPath, "/\\") . "/";
        $this->templateSubFolderPath = rtrim($appConfig->templateSubFolderPath, "/\\") . "/";
    }

    public function save($data, $isNew)
    {
        $folderName = $data["folder"] ?? "";

        if (!$folderName) {
            return;
        }

        $clientFolder = $this->clientsFolderPath . $folderName;
        $login = $data["login"] ?? null;
        $isSub = !empty($data["master"]);
        $templatePath = $isSub ? $this->templateSubFolderPath : $this->templateFolderPath;
        $masterFolderName = $data["masterFolder"] ?? "";

        if (!$isNew && !file_exists($clientFolder)) {
            return;
        }

        if ($isNew && !file_exists($templatePath)) {
            app_log(LOG_APP, "[ERROR] Client folder cannot be created because template folder not exists");
            return;
        }

        if ($isNew && file_exists($clientFolder)) {
            app_log(LOG_APP, "[ERROR] Client folder cannot be created because it already exists: {0}", [log_array([
                "folder" => $folderName,
                "login" => $login,
                "isSub" => $isSub,
                "masterFolder" => $masterFolderName,
            ])]);

            return;
        }

        $tariff = $data["tariff"] ?? null;
        $dateEnd = $data["date_end"] ?? null;
        $partner = $data["partner"] ?? null;

        if (isset($dateEnd)) {
            $dateEnd = $this->convertDate($dateEnd);
        }

        if ($isNew) {
            mkdir($clientFolder);
            $this->r_copy($templatePath, $clientFolder, 1);
        }

        $usrTxtPath = $clientFolder . "/config/data/usr.txt";

        $userdata = $isNew ? $login . ";asdf124" : null;

        if (!$userdata) {
            $userdata = file_get_contents($usrTxtPath);
            $userdata = explode(";", $userdata);
            $userdata[0] = trim($login);
            $userdata = implode(";", $userdata);
        }

        file_put_contents($usrTxtPath, $userdata);

        if ($isNew && $isSub) {
            $masterFolder = $this->clientsFolderPath . $masterFolderName;

            if (file_exists($masterFolder)) {
                $subTxtPath = $masterFolder . "/config/data/sub_accounts.txt";

                if (!file_exists($subTxtPath)) {
                    file_put_contents($subTxtPath, "");
                }

                $subaccs = file_get_contents($subTxtPath);
                $subaccs = explode(';', $subaccs);

                if (empty($subaccs[0])) {
                    $subaccs = [];
                }

                $subaccs[] = $folderName;
                $subaccs = trim(implode(";", $subaccs), ";");
                file_put_contents($subTxtPath, $subaccs);
            }
        }

        $ini = $this->parse_ini($folderName);

        if ($isNew) {
            $ini["tariff"]["active"] = 1;
        }

        $ini["tariff"]["id"] = $tariff;
        $ini["tariff"]["end_date"] = $dateEnd;

        if ($partner) {
            $ini["tariff"]["partner"] = $partner;
        } else if (array_key_exists("partner", $ini["tariff"])) {
            unset($ini["tariff"]["partner"]);
        }

        if ($isNew && $isSub) {
            $ini['tariff']['parent'] = $masterFolderName;
        }

        $this->write_ini($folderName, $ini);

        if ($isNew) {
            app_log(LOG_APP, "Client folder created: {0}", [log_array([
                "folder" => $folderName,
                "login" => $login,
                "isSub" => $isSub,
                "masterFolder" => $masterFolderName,
            ])]);
        }
    }

    public function resetPassword($folder)
    {
        if (!$this->checkClientFolder($folder)) {
            return;
        }

        $clientFolder = $this->clientsFolderPath . $folder;
        file_put_contents($clientFolder . "/pwdrst", "1");
    }

    public function convertDate($date)
    {
        if (gettype($date) !== "string") {
            return null;
        }

        if (strlen($date) >= 10) {
            $parts = explode("-", substr($date, 0, 10));

            if (count($parts) === 3 && strlen($parts[0]) === 4) {
                return implode(".", array_reverse($parts));
            }
        }

        return $date;
    }

    private function checkClientFolder($folder): bool
    {
        if (!$folder) {
            return false;
        }

        if (!file_exists($this->clientsFolderPath . $folder)) {
            return false;
        }

        return true;
    }

    private function parse_ini($folder)
    {
        $iniPath = $this->clientsFolderPath . $folder . "/config/data/config.ini";
        return parse_ini_file($iniPath, true);
    }

    private function write_ini($folder, $data)
    {
        $iniPath = $this->clientsFolderPath . $folder . "/config/data/config.ini";
        $this->config_write($data, $iniPath);
    }

    private function config_write($config_data, $config_file)
    {
        $new_content = "";

        foreach ($config_data as $section => $section_content) {
            $section_content = array_map(function ($value, $key) {
                return "$key=$value";
            }, array_values($section_content), array_keys($section_content));
            $section_content = implode("\n", $section_content);
            $new_content     .= "[$section]\n$section_content\n";
        }

        file_put_contents($config_file, $new_content);
    }

    private function r_copy($source, $dest, $overwrite = 0)
    {
        // Check for symlinks
        if (is_link($source)) {
            return symlink(readlink($source), $dest);
        }

        // Simple copy for a file
        if (is_file($source)) {
            if ($overwrite == 1) {
                return copy($source, $dest);
            } else {
                if (file_exists($dest)) {
                    return false;
                } else {
                    return copy($source, $dest);
                }
            }
        }

        // Make destination directory
        if (!is_dir($dest)) {
            mkdir($dest);
        }

        // Loop through the folder
        $dir = dir($source);
        while (false !== $entry = $dir->read()) {
            // Skip pointers
            if ($entry == '.' || $entry == '..') {
                continue;
            }

            // Deep copy directories
            $this->r_copy("$source/$entry", "$dest/$entry", $overwrite);
        }

        // Clean up
        $dir->close();
        return true;
    }
}
