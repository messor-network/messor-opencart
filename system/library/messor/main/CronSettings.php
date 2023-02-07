<?php

namespace main;

use src\Utils\File;
use src\Utils\Parser;

class CronSettings
{
    use CronCleanerData;
    use CronDatabaseData;
    use CronFileControlData;
}

trait CronCleanerData
{
    private $cleaner;

    public function setCleanerPath($path)
    {
        $this->cleaner['Cron_PATH'] = $path;
        return $this;
    }

    public function setCleanerExtensionsPHP($extensionsPHP)
    {
        $this->cleaner['Cron_EXTENSIONS_PHP'] = $extensionsPHP;
        return $this;
    }

    public function setCleanerExtensionsCGI($extensionsCGI)
    {
        $this->cleaner['Cron_EXTENSIONS_CGI'] = $extensionsCGI;
        return $this;
    }

    public function setCleanerSignatureFile($signatureFile)
    {
        $this->cleaner['Cron_SIGNATURE_FILE'] = $signatureFile;
        return $this;
    }

    public function setCleanerMaxNeedDetects($maxNeedDetect)
    {
        $this->cleaner['Cron_MAX_NEED_DETECTS'] = $maxNeedDetect;
        return $this;
    }

    public function setCleanerMaxFilesizePHPEnable($maxFileSizePHPEnable)
    {
        $this->cleaner['Cron_MAX_FILESIZE_PHP_ENABLE'] = $maxFileSizePHPEnable;
        return $this;
    }

    public function setCleanerMaxFilesizeCGIEnable($maxFileSizeCGIEnable)
    {
        $this->cleaner['Cron_MAX_FILESIZE_CGI_ENABLE'] = $maxFileSizeCGIEnable;
        return $this;
    }

    public function setCleanerMaxFilesizeMB($fileSizeMB)
    {
        $this->cleaner['Cron_MAX_FILESIZE_MB'] = $fileSizeMB;
        return $this;
    }

    // todo no use now
    public function setCleanerExclude($exclude)
    {
        $this->cleaner['Cron_EXCLUDE'] = $exclude;
        return $this;
    }

    public function setCleanerExcludeFiles($excludeFiles)
    {
        $this->cleaner['Cron_EXCLUDE_FILES'] = $excludeFiles;
        return $this;
    }

    public function setCleanerSignatureVersion($signatureVersion)
    {
        $this->cleaner['Cron_signature_version'] = $signatureVersion;
        return $this;
    }

    public function setCleanerSignaturePHP($signaturePHP)
    {
        $this->cleaner['Cron_SIGNATURE_PHP'] = $signaturePHP;
        return $this;
    }

    public function setCleanerSignatureCGI($signatureCGI)
    {
        $this->cleaner['Cron_SIGNATURE_CGI'] = $signatureCGI;
        return $this;
    }

    public function saveCleanerConfig()
    {
        $file = BASE_PATH . '/modules/cleaner/database/cron_settings.txt';
        File::clear($file);
        return File::write($file, Parser::toSettingArray($this->cleaner));
    }

    public function getCleanerConfigOfFile()
    {
        $file = BASE_PATH . '/modules/cleaner/database/cron_settings.txt';
        $fileRead = File::read($file);
        if ($fileRead == null) {
            $this->getCleanerDefaultSetting();
            $this->saveCleanerConfig();
            $response = $this->getCleanerConfigOfFile();
        } else {
            $response = Parser::toArraySetting($fileRead);
        }
        return $response;
    }

    // todo no use now
    public function saveCleanerExclude()
    {
        $file = BASE_PATH . '/modules/cleaner/database/cron_exclude.txt';
        File::clear($file);
        File::write($file, Parser::toString($this->cleaner));
    }

    // todo no use now
    public function getCleanerExcludeOfFile()
    {
        $file = BASE_PATH . '/modules/cleaner/database/cron_exclude.txt';
        $response = Parser::toArray(File::read($file));
        return $response;
    }

    public function getCleanerDefaultSetting()
    {
        $this->cleaner = array(
            "Cron_PATH" => $this->cleaner['Cron_PATH'],
            "Cron_EXTENSIONS_PHP" => ".php .phtm .inc .tpl",
            "Cron_EXTENSIONS_CGI" => ".cgi .pl .pm .perl .py .vb .asp .aspx",
            "Cron_SIGNATURE_FILE" => BASE_PATH . "/extension/messor/system/library/modules/cleaner/database/s.txt",
            "Cron_MAX_NEED_DETECTS" => 10,
            "Cron_MAX_FILESIZE_PHP_ENABLE" => "1",
            "Cron_MAX_FILESIZE_CGI_ENABLE" => "1",
            "Cron_MAX_FILESIZE_MB" => 1,
            "Cron_EXCLUDE_FILES" => BASE_PATH . "/extension/messor/system/library/modules/cleaner/MCleaner.php\n" . BASE_PATH . "extension/messor/system/library/modules/fdbbackup/FDBBackup.php\n",
            "Cron_signature_version" => "Version 25.07.2022",
            "Cron_SIGNATURE_PHP" => "",
            "Cron_SIGNATURE_CGI" => ""
        );
    }

    public function getCleanerTextCronPath()
    {
        return "0 0 * * 0 php " . __DIR__ . "/Cron.php cleaner";
    }
}

trait CronDatabaseData
{
    private $backup;
    public function getDatabaseData()
    {
        $file = BASE_PATH . '/modules/fdbbackup/data/cron_database.txt';
        $fileRead = File::read($file);
        return $fileRead != null ? Parser::toArraySetting($fileRead) : null;
    }

    public function saveDatabaseData($host, $user, $password, $dbname)
    {
        $database['cron_host'] = $host;
        $database['cron_user'] = $user;
        $database['cron_password'] = $password;
        $database['cron_dbname'] = $dbname;
        $file = BASE_PATH . '/modules/fdbbackup/data/cron_database.txt';
        File::clear($file);
        File::write($file, Parser::toSettingArray($database));
    }

    public function getDatabaseTables()
    {
        $file = BASE_PATH . '/modules/fdbbackup/data/cron_tables.txt';
        $fileRead = File::read($file);
        return $fileRead != null ? Parser::toArray($fileRead) : array();
    }

    public function saveDatabaseTables($tables)
    {
        $file = BASE_PATH . '/modules/fdbbackup/data/cron_tables.txt';
        File::clear($file);
        if ($tables != null) {
            File::write($file, Parser::toString($tables));
        }
    }

    public function setDatabasePath($path)
    {
        $this->backup['cron_path'] = $path;
        return $this;
    }

    public function setDatabaseArchivation($archivation)
    {
        $this->backup['cron_type_arch'] = $archivation;
        return $this;
    }

    public function setDatabaseTypeBackup($typeBackup)
    {
        $this->backup['cron_type_backup'] = $typeBackup;
        return $this;
    }

    public function setDatabaseEmail($email)
    {
        $this->backup['cron_email_user'] = $email;
        return $this;
    }

    public function setDatabaseSMTP($url, $port, $login, $password)
    {
        $this->backup['cron_smtp_url'] = $url;
        $this->backup['cron_smtp_port'] = $port;
        $this->backup['cron_smtp_login'] = $login;
        $this->backup['cron_smtp_password'] = $password;
        return $this;
    }

    public function setDatabaseAction($action)
    {
        $this->backup['cron_action'] = $action;
        return $this;
    }

    public function getDatabaseExcludeOfFile()
    {
        $file = BASE_PATH . '/modules/fdbbackup/data/cron_exclude.txt';
        $fileRead = File::read($file);
        return $fileRead != null ? Parser::toArray($fileRead) : null;
    }

    public function saveDatabaseExclude($exclude)
    {
        $file = BASE_PATH . '/modules/fdbbackup/data/cron_exclude.txt';
        File::clear($file);
        File::write($file, Parser::toString($exclude));
    }

    public function saveDatabaseConfig()
    {
        $file = BASE_PATH . '/modules/fdbbackup/data/cron_settings.txt';
        File::clear($file);
        return File::write($file, Parser::toSettingArray($this->backup));
    }

    public function getDatabaseConfigOfFile()
    {
        $file = BASE_PATH . '/modules/fdbbackup/data/cron_settings.txt';
        $fileRead = File::read($file);
        if ($fileRead == null) {
            $this->setDatabaseConfigDefault();
            $this->saveDatabaseConfig();
            $response = $this->getDatabaseConfigOfFile();
        } else {
            $response = Parser::toArraySetting($fileRead);
        }
        return $response;
    }

    public function setDatabaseConfigDefault()
    {
        $this->backup = array(
            "cron_path" => $this->backup['cron_path'],
            "cron_type_arch" => ".zip",
            "cron_type_backup" => "backup_db",
            "cron_email_user" => "",
            "cron_smtp_url" => "",
            "cron_smtp_port" => "",
            "cron_smtp_login" => "",
            "cron_smtp_password" => "",
            "cron_action" => "save"
        );
    }

    public function getDumpName($typeName)
    {
        return "dump_" . date("d_m_y") . "_$typeName";
    }

    public function getDatabaseTextCronPath()
    {
        return "0 0 * * 0 php " . __DIR__ . "/Cron.php backup";
    }
}

trait CronFileControlData
{
    private $fileControl;

    public function setFileControlPath($path)
    {
        $this->fileControl['cron_path'] = $path;
        return $this;
    }

    public function setFileControlExclude($exclude)
    {
        $this->fileControl['cron_exclude'] = $exclude;
        return $this;
    }

    public function saveFileControlConfig()
    {
        $file = BASE_PATH . '/modules/fscontroll/data/cron_settings.txt';
        File::clear($file);
        return File::write($file, Parser::toSettingArray($this->fileControl));
    }

    public function getFileControlConfigOfFile()
    {
        $file = BASE_PATH . '/modules/fscontroll/data/cron_settings.txt';
        $fileRead = File::read($file);
        if ($fileRead == null) {
            $this->setFileControlConfigDefault();
            $this->saveFileControlConfig();
            $response = $this->getFileControlConfigOfFile();
        } else {
            $response = Parser::toArraySetting($fileRead);
        }
        return $response;
    }

    public function setFileControlConfigDefault()
    {
        $this->fileControl = array(
            'cron_path' => $this->fileControl['cron_path'],
            'cron_exclude' => '',
        );
    }

    public function getFileControlTextCronPath()
    {
        return "0 0 * * 0 php " . __DIR__ . "/Cron.php file_control";
    }
}
