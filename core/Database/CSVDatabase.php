<?php

namespace core\Database;

use core\App;

class CSVDatabase
{
    /**
     * Either provide the full path of the file or just the filename (Must be located in \resources\data\ folder)
     * @param $filename
     * @return array
     */
    public static function returnAllData($filename): array
    {
        $path = @file_exists($filename) ? $filename : App::$app->config['resources_path'] . '/data/' . $filename;
        $handler = fopen($path, 'r');
        $data = [];

        if ($handler !== false) {
            while ($row = fgetcsv($handler)) {
                $data[] = $row;
            }
            fclose($handler);
        }

        return $data;
    }
}