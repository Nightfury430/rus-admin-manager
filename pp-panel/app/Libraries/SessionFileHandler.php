<?php

declare(strict_types=1);

namespace App\Libraries;

use CodeIgniter\Session\Handlers\FileHandler as BaseFileHandler;

class SessionFileHandler extends BaseFileHandler
{
    public function read($id)
    {
        // This might seem weird, but PHP 5.6 introduced session_reset(),
        // which re-reads session data
        if ($this->fileHandle === null) {
            $this->fileNew = ! is_file($this->filePath . $id);

            if (($this->fileHandle = fopen($this->filePath . $id, 'c+b')) === false) {
                $this->logger->error("Session: Unable to open file '" . $this->filePath . $id . "'.");

                return false;
            }

            if (flock($this->fileHandle, LOCK_EX) === false) {
                $this->logger->error("Session: Unable to obtain lock for file '" . $this->filePath . $id . "'.");
                fclose($this->fileHandle);
                $this->fileHandle = null;

                return false;
            }

            if (! isset($this->sessionID)) {
                $this->sessionID = $id;
            }

            if ($this->fileNew) {
                @chmod($this->filePath . $id, 0600);
                $this->fingerprint = md5('');

                return '';
            }
        } else {
            rewind($this->fileHandle);
        }

        $data   = '';
        $buffer = 0;
        clearstatcache(); // Address https://github.com/codeigniter4/CodeIgniter4/issues/2056

        for ($read = 0, $length = filesize($this->filePath . $id); $read < $length; $read += strlen($buffer)) {
            if (($buffer = fread($this->fileHandle, $length - $read)) === false) {
                break;
            }

            $data .= $buffer;
        }

        $this->fingerprint = md5($data);

        return $data;
    }
}
