<?php

declare(strict_types=1);

namespace App\Libraries;

use CodeIgniter\Log\Handlers\FileHandler as BaseFileHandler;
use DateTime;
use Exception;

class LogFileHandler extends BaseFileHandler
{
    /**
     * Handles logging the message.
     * If the handler returns false, then execution of handlers
     * will stop. Any handlers that have not run, yet, will not
     * be run.
     *
     * @param string $level
     * @param string $message
     *
     * @throws Exception
     */
    public function handle($level, $message): bool
    {
        $filepath = $this->path . 'ci-' . date('Y-m-d') . '.' . $this->fileExtension;

        $msg = '';

        if (! is_file($filepath)) {
            $newfile = true;

            // Only add protection to php files
            if ($this->fileExtension === 'php') {
                $msg .= "<?php defined('SYSTEMPATH') || exit('No direct script access allowed'); ?>\n\n";
            }
        }

        if (! $fp = @fopen($filepath, 'ab')) {
            return false;
        }

        // Instantiating DateTime with microseconds appended to initial date is needed for proper support of this format
        if (str_contains($this->dateFormat, 'u')) {
            $microtimeFull  = microtime(true);
            $microtimeShort = sprintf('%06d', ($microtimeFull - floor($microtimeFull)) * 1_000_000);
            $date           = new DateTime(date('Y-m-d H:i:s.' . $microtimeShort, (int) $microtimeFull));
            $date           = $date->format($this->dateFormat);
        } else {
            $date = date($this->dateFormat);
        }

        $msg .= strtoupper($level) . ' - ' . $date . ' --> ' . $message . "\n";

        flock($fp, LOCK_EX);

        $result = null;

        for ($written = 0, $length = strlen($msg); $written < $length; $written += $result) {
            if (($result = fwrite($fp, substr($msg, $written))) === false) {
                // if we get this far, we'll never see this during travis-ci
                // @codeCoverageIgnoreStart
                break;
                // @codeCoverageIgnoreEnd
            }
        }

        flock($fp, LOCK_UN);
        fclose($fp);

        if (isset($newfile) && $newfile === true) {
            @chmod($filepath, $this->filePermissions);
        }

        return is_int($result);
    }
}
