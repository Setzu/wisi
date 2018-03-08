<?php
/**
 * Created by PhpStorm.
 * User: david b. <dav.bla28@gmail.com>
 * Date: 02/03/18
 * Time: 13:16
 */

namespace Wisi\Model;


use Wisi\Services\Logs;

class ConnectionModel
{

    const DEFAULT_DESCRIPTION = 'Ajouté depuis wisi';

    protected $connexion;
    private $database = 'D6022b34';
    private $host = 'S65ff17d';
    private $user = 'GFPADM';
    private $password = 'GFPADM40';

    /**
     * ConnectionPDO constructor.
     * @param array $aSystemInfos
     */
    public function __construct(array $aSystemInfos = [])
    {
        if (is_array($aSystemInfos) && count($aSystemInfos) > 0) {
            $this->setDatabase($aSystemInfos['DBNAME']);
            $this->setHost($aSystemInfos['IPADR']);
            $this->setUser($aSystemInfos['NMUSR']);
            $this->setPassword(base64_decode($aSystemInfos['PWUSR']));
        }

        $this->setConnexion();
    }


    /**
     * @return mixed
     */
    public function getDatabase()
    {
        return $this->database;
    }

    /**
     * @param mixed $database
     */
    public function setDatabase($database)
    {
        $this->database = $database;
    }

    /**
     * @return mixed
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @param mixed $host
     */
    public function setHost($host)
    {
        $this->host = $host;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return \PDO
     */
    public function getConnexion()
    {
        return $this->connexion;
    }

    /**
     * @return bool
     */
    public function setConnexion()
    {
        $this->connexion = '';

        $aConnectionParams = 'odbc:DRIVER={IBM i Access ODBC Driver};DATABASE=' . $this->getDatabase() . ';SYSTEM=' .
            $this->getHost() . ';PROTOCOL=TCPIP;UID=' . $this->getUser() . ';PWD= ' . $this->getPassword() . ';NAM=1;';
//        $aConnectionParams = "odbc:DRIVER={IBM DB2 ODBC DRIVER};HOSTNAME=" . $this->getHost() . ";PORT=50000;DATABASE=" .
//            $this->getDatabase() . ";PROTOCOL=TCPIP;UID=" . $this->getUser() . ";PWD=" . $this->getPassword() . ";";

        try {
            // Limitation à quelques secondes car si, pour une raison ou une autre, la connexion a un système ne se fait
            // pas, l'ajax mettra 30 secondes à rafraichir le contenu de l'index
            set_time_limit(3);
            $this->connexion = new \PDO($aConnectionParams, $this->getUser(), $this->getPassword(), [\PDO::ATTR_PERSISTENT => true]);
        } catch (\PDOException $e) {
            Logs::add($e->getMessage() . ' in ' . __FILE__ . ' at line ' . __LINE__);

            return false;
        }

        return true;
    }

    /**
     * @param array $aSystemInfos
     * @return bool
     */
    public function insertConnection(array $aSystemInfos)
    {
        $con = $this->getConnexion();

        if (!$con instanceof \PDO) {
            $aErrorInfos = $con->errorInfo();
            Logs::add($aErrorInfos[2] . ' in ' . __FILE__ . ' at line ' . __LINE__);

            return false;
        }

        $query = 'INSERT INTO GFPSYSGES.SSYPR1P0 (NMSYS, SYSNAME, DESCRIPT, IPADR, NMUSR, PWUSR, DBNAME, SYSTEMTYP, COLOR, SYSPTY)
    VALUES (:NMSYS, :SYSNAME, :DESCRIPT, :IPADR, :NMUSR, :PWUSR, :DBNAME, :SYSTEMTYP, :COLOR, :SYSPTY)';

        if (!$stmt = $con->prepare($query)) {
            $aErrorInfos = $con->errorInfo();
            Logs::add($aErrorInfos[2] . ' in ' . __FILE__ . ' at line ' . __LINE__);

            return false;
        }

        $sDescription = self::DEFAULT_DESCRIPTION;

        try {
            $stmt->bindParam(':NMSYS', $aSystemInfos['NMSYS']);
            $stmt->bindParam(':SYSNAME', $aSystemInfos['SYSNAME']);
            $stmt->bindParam(':DESCRIPT', $sDescription);
            $stmt->bindParam(':IPADR', $aSystemInfos['IPADR']);
            $stmt->bindParam(':NMUSR', $aSystemInfos['NMUSR']);
            $stmt->bindParam(':PWUSR', $aSystemInfos['PWUSR']);
            $stmt->bindParam(':DBNAME', $aSystemInfos['DBNAME']);
            $stmt->bindParam(':SYSTEMTYP', $aSystemInfos['SYSTEMTYP']);
            $stmt->bindParam(':COLOR', $aSystemInfos['COLOR']);
            $stmt->bindParam(':SYSPTY', $aSystemInfos['SYSPTY']);

            if (!$stmt->execute()) {
                $aErrorInfos = $con->errorInfo();
                Logs::add($aErrorInfos[2] . ' in ' . __FILE__ . ' at line ' . __LINE__);

                return false;
            }

            $stmt->closeCursor();
        } catch (\PDOException $e) {
            Logs::add($e->getMessage() . ' in ' . __FILE__ . ' at line ' . __LINE__);

            return false;
        }

        return true;
    }

    /**
     * @param string $sSystemName
     * @return bool
     */
    public function isConnectionExists($sSystemName)
    {
        $con = $this->getConnexion();

        if (!$con instanceof \PDO) {
            $aErrorInfos = $con->errorInfo();
            Logs::add($aErrorInfos[2] . ' in ' . __FILE__ . ' at line ' . __LINE__);

            return false;
        }

        $query = 'SELECT count(*) FROM GFPSYSGES.SSYPR1P0 WHERE NMSYS = :NMSYS';

        if (!$stmt = $con->prepare($query)) {
            $aErrorInfos = $con->errorInfo();
            Logs::add($aErrorInfos[2] . ' in ' . __FILE__ . ' at line ' . __LINE__);

            return false;
        }

        try {
            $stmt->bindParam(':NMSYS', $sSystemName);

            if (!$stmt->execute()) {
                $aErrorInfos = $con->errorInfo();
                Logs::add($aErrorInfos[2] . ' in ' . __FILE__ . ' at line ' . __LINE__);

                return false;
            }

            $bExists = (bool) $stmt->fetch(\PDO::FETCH_COLUMN);
            $stmt->closeCursor();
        } catch (\PDOException $e) {
            Logs::add($e->getMessage() . ' in ' . __FILE__ . ' at line ' . __LINE__);

            return false;
        }

        return $bExists;
    }

    /**
     * @TODO : élucider le mystère sur la disparition de la machine MERCER...
     *
     * @return array|bool
     */
    public function selectAllConnections()
    {
        $con = $this->getConnexion();

        if (!$con instanceof \PDO) {
            $aErrorInfos = $con->errorInfo();
            Logs::add($aErrorInfos[2] . ' in ' . __FILE__ . ' at line ' . __LINE__);

            return false;
        }

        $query = 'SELECT NMSYS, SYSNAME, IPADR, NMUSR, PWUSR, DBNAME, SYSTEMTYP, COLOR, SYSPTY FROM GFPSYSGES.SSYPR1P0 ORDER BY SYSPTY ASC';

        if (!$stmt = $con->prepare($query)) {
            $aErrorInfos = $con->errorInfo();
            Logs::add($aErrorInfos[2] . ' in ' . __FILE__ . ' at line ' . __LINE__);

            return false;
        }

        try {
            if (!$stmt->execute()) {
                $aErrorInfos = $con->errorInfo();
                Logs::add($aErrorInfos[2] . ' in ' . __FILE__ . ' at line ' . __LINE__);

                return false;
            }

            $aResults = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            $stmt->closeCursor();
        } catch (\PDOException $e) {
            Logs::add($e->getMessage() . ' in ' . __FILE__ . ' at line ' . __LINE__);

            return false;
        }

        return $aResults;
    }

    /**
     * @param array $aSystemInfos
     * @return bool
     */
    public static function testConnection(array $aSystemInfos)
    {
        $aParams = 'odbc:DRIVER={IBM i Access ODBC Driver};DATABASE=' . $aSystemInfos['bdd'] . ';SYSTEM=' . $aSystemInfos['host'] .
            ';PROTOCOL=TCPIP;UID=' . $aSystemInfos['user'] . ';PWD= ' . $aSystemInfos['password'] . ';NAM=1;';

        try {
            // Limitation à quelques secondes pour éviter les timeout
            set_time_limit(2);
            $con = new \PDO($aParams, $aSystemInfos['user'], $aSystemInfos['password'], [\PDO::ATTR_PERSISTENT => TRUE]);

            if (!$con) {
                $aErrorInfos = $con->errorInfo();
                Logs::add($aErrorInfos[2] . ' in ' . __FILE__ . ' at line ' . __LINE__);

                return false;
            }
        } catch (\PDOException $e) {
            Logs::add($e->getMessage() . ' in ' . __FILE__ . ' at line ' . __LINE__);

            return false;
        }

        return true;
    }

    /**
     * @param int $priority
     * @return array
     */
    public function selectHigherPriority($priority)
    {
        $con = $this->getConnexion();

        if (!$con instanceof \PDO) {
            $aErrorInfos = $con->errorInfo();
            Logs::add($aErrorInfos[2] . ' in ' . __FILE__ . ' at line ' . __LINE__);

            return [];
        }

        $query = 'SELECT NMSYS, SYSPTY FROM GFPSYSGES.SSYPR1P0 WHERE SYSPTY >= :SYSPTY';
        $iPriority = (int) $priority;

        if ($iPriority == 0) {
            Logs::add('La priorité doit être supérieur à 0. ' . __FILE__ . ' at line ' . __LINE__);

            return [];
        }

        try {
            $stmt = $con->prepare($query);
            $stmt->bindParam(':SYSPTY', $iPriority);
            $stmt->execute();
            $aResults = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            $stmt->closeCursor();
        } catch (\PDOException $e) {
            Logs::add($e->getMessage() . ' in ' . __FILE__ . ' at line ' . __LINE__);

            return [];
        }

        return $aResults;
    }

    /**
     * @param string $system
     * @param int $priority
     * @return array
     */
    public function updatePriorityBySystemAndPriority($system, $priority)
    {
        $con = $this->getConnexion();

        if (!$con instanceof \PDO) {
            $aErrorInfos = $con->errorInfo();
            Logs::add($aErrorInfos[2] . ' in ' . __FILE__ . ' at line ' . __LINE__);

            return [];
        }

        $query = 'UPDATE GFPSYSGES.SSYPR1P0 SET SYSPTY = :SYSPTY WHERE NMSYS = :NMSYS';
        $iPriority = (int) $priority;

        try {
            $stmt = $con->prepare($query);
            $stmt->bindParam(':SYSPTY', $iPriority);
            $stmt->bindParam(':NMSYS', $system);
            $stmt->execute();
            $aResults = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            $stmt->closeCursor();
        } catch (\PDOException $e) {
            Logs::add($e->getMessage() . ' in ' . __FILE__ . ' at line ' . __LINE__);

            return [];
        }

        return $aResults;
    }
}