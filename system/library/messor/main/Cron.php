<?php

namespace main;

require realpath(__DIR__.'/..').'/autoloader.php';

use main\Adapter;
use main\CronSettings;

class Cron
{
    public function init($argv)
    {
        switch ($argv[1]) {
            case 'cleaner':
                self::callCleaner();
                break;
            case 'backup':
                self::callBackup();
                break;
            case 'file_control':
                self::callFileControl();
                break;
        }
    }

    public static function callCleaner()
    {
        $cronSettings = new CronSettings();
        $cronSettingsCleaner = $cronSettings->getCleanerConfigOfFile();
        foreach ($cronSettingsCleaner as $key => $value) {
            $newKey = str_replace('Cron_', '', $key);
            $cronSettingsCleaner[$newKey] = $value;
            unset($cronSettingsCleaner[$key]);
        }
        $adapter = new Adapter();
        $MCleaner = $adapter->MessorLib->MCleaner();
        $data = $adapter->MCLApiResult($cronSettingsCleaner);
        list($level, $result) = $MCleaner->dataForNotify($data);
        $adapter->MessorLib->notifyOnServer('cleaner', $level, $result);
    }

    public function callBackup()
    {
        $cronSettings = new CronSettings();
        $cronSettingsDatabase = $cronSettings->getDatabaseConfigOfFile();
        foreach ($cronSettingsDatabase as $key => $value) {
            $newKey = str_replace('cron_', '', $key);
            $cronSettingsDatabase[$newKey] = $value;
            unset($cronSettingsDatabase[$key]);
        }
        $adapter = new Adapter();
        $FDBBackup = $adapter->MessorLib->FDBBackup(null);
        $database = $cronSettings->getDatabaseData();
        foreach ($database as $key => $value) {
            $newKey = str_replace('cron_', '', $key);
            $database[$newKey] = $value;
            unset($database[$key]);
        }
        if ($cronSettingsDatabase['type_backup'] == "backup_file_db") {
            $cronSettingsDatabase['type_backup'] = "backup_db";
            $cronSettingsDatabase['filename'] = $cronSettings->getDumpName("db");
            $cronSettingsDatabase['tables'] = $cronSettings->getDatabaseTables();
            $data = $adapter->FDBBApiResult($cronSettingsDatabase, $database);
            list($level, $result) = $FDBBackup->dataForNotify();
            $response = $adapter->MessorLib->notifyOnServer('fdbackup', $level, $result);
            $cronSettingsDatabase['type_backup'] = "backup_file";
            $cronSettingsDatabase['filename'] = $cronSettings->getDumpName("file");
            $cronSettingsDatabase['tables'] = $cronSettings->getDatabaseTables();
            $data = $adapter->FDBBApiResult($cronSettingsDatabase, $database);
            list($level, $result) = $FDBBackup->dataForNotify();
            $response = $adapter->MessorLib->notifyOnServer('fdbackup', $level, $result);
            return;
        } else {
            $filenameType = str_replace('backup_', '', $cronSettingsDatabase['type_backup']);
            $cronSettingsDatabase['filename'] = $cronSettings->getDumpName($filenameType);
            $cronSettingsDatabase['tables'] = $cronSettings->getDatabaseTables();
            $data = $adapter->FDBBApiResult($cronSettingsDatabase, $database);
            list($level, $result) = $FDBBackup->dataForNotify();
            $response = $adapter->MessorLib->notifyOnServer('fdbackup', $level, $result);
            return;
        }
    }

    public function callFileControl()
    {
        $cronSettings = new CronSettings();
        $cronSettingsFileControl = $cronSettings->getFileControlConfigOfFile();
        foreach ($cronSettingsFileControl as $key => $value) {
            $newKey = str_replace('cron_', '', $key);
            $cronSettingsFileControl[$newKey] = $value;
            unset($cronSettingsFileControl[$key]);
        }
        $adapter = new Adapter();
        $FSControll = $adapter->MessorLib->FSControll();
        $data = $adapter->FSControlApiResult($cronSettingsFileControl);
        list($level, $result) = $FSControll->dataForNotify($data);
        $adapter->MessorLib->notifyOnServer('fsc', $level, $result);
    }
}
(new Cron)->init($argv);
