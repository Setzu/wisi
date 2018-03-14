<?php
/**
 * Created by PhpStorm.
 * User: david b.
 * Date: 30/01/18
 * Time: 08:46
 */

namespace Wisi\Services;


class Logs
{

    const BASE_FILE_PATH = '/../../../data/logs/';

    /**
     * @param mixed $log
     * @param string $fileName
     * @return bool
     */
    static public function add($log, $fileName = '')
    {
        if (!is_string($fileName) || empty($fileName)) {
            $fileName = 'logs';
        }

        $sFile = __DIR__ . self::BASE_FILE_PATH . $fileName . '.txt';

        try{
            $logFile = fopen($sFile, 'a+');
            $content = date('d/m/y H:i:s ');

            if (is_array($log)) {
                foreach ($log as $k => $v) {
                    if (is_string($v) || is_int($v)) {
                        $content .= $k . ' => ' . $v . PHP_EOL;
                    }
                }
            } else {
                $content .= $log . PHP_EOL;
            }

            fputs($logFile, $content);

            return fclose($logFile);
        } catch(\Exception $e) {
            // die($e->getMessage());

            return false;
        }
    }
}