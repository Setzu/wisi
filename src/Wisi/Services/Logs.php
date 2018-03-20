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
     * @param string $system
     * @param string $log
     * @param string $file
     * @param string $line
     * @return bool
     */
    static public function add($system, $log, $file = '', $line = '')
    {
        $sLogsFile = __DIR__ . self::BASE_FILE_PATH . 'logs.txt';

        try{
            $logFile = fopen($sLogsFile, 'a+');

            if ($logFile == false) {
                return self::addBDDLog($system, $log, $file, $line);
            }

            $content = date('d/m/y H:i:s');
            $content .= ' system : ' . $system;
            $content .= ' log : ' . $log;
            $content .= ' in ' . $file;
            $content .= ' at line ' . $line . PHP_EOL;

            fputs($logFile, $content);

            return fclose($logFile);
        } catch(\Exception $e) {
            // die($e->getMessage());

            return false;
        }
    }

    /**
     * @param string $system
     * @param string $log
     * @param string $file
     * @param string $line
     * @return bool
     */
    static public function addBDDLog($system, $log, $file = '', $line = '')
    {
        if (!file_exists(__DIR__ . '/../../../config/bdd.php')) {
            return false;
        }

        $bddConf = require __DIR__ . '/../../../config/bdd.php';

        $sConnectionParams = 'odbc:DRIVER={IBM i Access ODBC Driver};DATABASE=' . $bddConf['db2']['database'] . ';SYSTEM=' .
            $bddConf['db2']['host'] . ';PROTOCOL=TCPIP;UID=' . $bddConf['db2']['user'] . ';PWD=' . $bddConf['db2']['password'] . ';NAM=1;';

        try {
            $con = new \PDO($sConnectionParams, $bddConf['db2']['user'], $bddConf['db2']['password'], [\PDO::ATTR_PERSISTENT => true]);

            if (!$con instanceof \PDO) {
                return false;
            }

            if (strlen($log) > 255) {
                $log = substr($log, 0, 255);
            }

            $query = 'INSERT INTO GFPSYSGES.SSYLOGP0 (SYSTEM, MSGLOG, PHPFILE, PHPLINE) VALUES (:SYSTEM, :MSGLOG, :PHPFILE, :PHPLINE)';

            if (!$stmt = $con->prepare($query)) {
//                $con->errorInfo();

                return false;
            }

            try {
                $stmt->bindParam(':SYSTEM', $system);
                $stmt->bindParam(':MSGLOG', $log);
                $stmt->bindParam(':PHPFILE', $file);
                $stmt->bindParam(':PHPLINE', $line);
                $stmt->execute();
                $stmt->closeCursor();
            } catch (\PDOException $e) {
//                $e->getMessage();

                return false;
            }

            return true;

        } catch (\PDOException $e) {
//            $e->getMessage();

            return false;
        }
    }
}