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
    private $database;
    private $host;
    private $user;
    private $password;

    /**
     * ConnectionPDO constructor.
     * @param array $aSystemInfos
     * @throws \Exception
     */
    public function __construct(array $aSystemInfos = [])
    {
        if (is_array($aSystemInfos) && count($aSystemInfos) > 0) {
            $this->setDatabase($aSystemInfos['DBNAME']);
            $this->setHost($aSystemInfos['IPADR']);
            $this->setUser($aSystemInfos['NMUSR']);
            $this->setPassword(base64_decode($aSystemInfos['PWUSR']));
        } else {

            if (!file_exists(__DIR__ . '/../../../config/bdd.php')) {
                throw new \Exception('Le fichier de configuration da la BDD est introuvable');
            }

            $bddConf = require __DIR__ . '/../../../config/bdd.php';

            $this->setDatabase($bddConf['db2']['database']);
            $this->setHost($bddConf['db2']['host']);
            $this->setUser($bddConf['db2']['user']);
            $this->setPassword($bddConf['db2']['password']);
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

        // Impossible de récupérer à chaque fois tous les systèmes avec ce profil
        $this->test = $sConnectionParams = 'odbc:DRIVER={IBM i Access ODBC Driver};DATABASE=' . $this->getDatabase() . ';SYSTEM=' .
            $this->getHost() . ';PROTOCOL=TCPIP;UID=' . $this->getUser() . ';PWD=' . $this->getPassword() . ';NAM=1;';

        // Aucun problème avec mon profil AS400
//        $sConnectionParams = 'odbc:DRIVER={IBM i Access ODBC Driver};DATABASE=' . $this->getDatabase() . ';SYSTEM=' .
//            $this->getHost() . ';PROTOCOL=TCPIP;UID=QPGMRDB;PWD=QPGMRDB;NAM=1;';

        try {
            // Limitation à quelques secondes pour éviter les timeout
            set_time_limit(3);
            $this->connexion = new \PDO($sConnectionParams, $this->getUser(), $this->getPassword(), [\PDO::ATTR_PERSISTENT => true]);
        } catch (\PDOException $e) {
            Logs::add($this->getHost(), 'Paramètres de connection à PDO : ' . $sConnectionParams, __FILE__, __LINE__);
            Logs::add($this->getHost(), $e->getMessage(), __FILE__, __LINE__);

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
            return false;
        }

        $query = 'INSERT INTO GFPSYSGES.SSYPR1P0 (NMSYS, SYSNAME, DESCRIPT, IPADR, NMUSR, PWUSR, DBNAME, SYSTEMTYP, COLOR, SYSPTY)
    VALUES (:NMSYS, :SYSNAME, :DESCRIPT, :IPADR, :NMUSR, :PWUSR, :DBNAME, :SYSTEMTYP, :COLOR, :SYSPTY)';

        if (!$stmt = $con->prepare($query)) {
            $aErrorInfos = $con->errorInfo();
            Logs::add($this->getHost(), $aErrorInfos[2], __FILE__, __LINE__);

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
                Logs::add($this->getHost(), $aErrorInfos[2], __FILE__, __LINE__);

                return false;
            }

            $stmt->closeCursor();
        } catch (\PDOException $e) {
            Logs::add($this->getHost(), $e->getMessage(), __FILE__, __LINE__);

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
            return false;
        }

        $query = 'SELECT count(*) FROM GFPSYSGES.SSYPR1P0 WHERE NMSYS = :NMSYS';

        if (!$stmt = $con->prepare($query)) {
            $aErrorInfos = $con->errorInfo();
            Logs::add($this->getHost(), $aErrorInfos[2], __FILE__, __LINE__);

            return false;
        }

        try {
            $stmt->bindParam(':NMSYS', $sSystemName);

            if (!$stmt->execute()) {
                $aErrorInfos = $con->errorInfo();
                Logs::add($this->getHost(), $aErrorInfos[2], __FILE__, __LINE__);

                return false;
            }

            $bExists = (bool) $stmt->fetch(\PDO::FETCH_COLUMN);
            $stmt->closeCursor();
        } catch (\PDOException $e) {
            Logs::add($this->getHost(), $e->getMessage(), __FILE__, __LINE__);

            return false;
        }

        return $bExists;
    }

    /**
     * @return array
     */
    public function selectAllConnections()
    {
        $con = $this->getConnexion();

        if (!$con instanceof \PDO) {
            return [];
        }

        $query = 'SELECT NMSYS, SYSNAME, IPADR, NMUSR, PWUSR, DBNAME, SYSTEMTYP, COLOR, SYSPTY, BORDER FROM GFPSYSGES.SSYPR1P0 ORDER BY SYSPTY ASC';

        if (!$stmt = $con->prepare($query)) {
            $aErrorInfos = $con->errorInfo();
            Logs::add($this->getHost(), $aErrorInfos[2], __FILE__, __LINE__);

            return [];
        }

        try {
            if (!$stmt->execute()) {
                $aErrorInfos = $con->errorInfo();
                Logs::add($this->getHost(), $aErrorInfos[2], __FILE__, __LINE__);

                return [];
            }

            $aResults = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            $stmt->closeCursor();
        } catch (\PDOException $e) {
            Logs::add($this->getHost(), $e->getMessage(), __FILE__, __LINE__);

            return [];
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
                return false;
            }
        } catch (\PDOException $e) {
            Logs::add($aSystemInfos['host'], $e->getMessage(), __FILE__, __LINE__);

            return false;
        }

        return true;
    }

    /**
     * @param array $aInfosSystem
     * @return bool
     */
    public function updateSystem(array $aInfosSystem)
    {
        $con = $this->getConnexion();

        if (!$con instanceof \PDO) {
            return false;
        }

        $query = 'UPDATE GFPSYSGES.SSYPR1P0 SET SYSNAME = :SYSNAME, SYSTEMTYP = :SYSTEMTYP, COLOR = :COLOR, SYSPTY = :SYSPTY WHERE NMSYS = :NMSYS';

        if (!$stmt = $con->prepare($query)) {
            $aErrorInfos = $con->errorInfo();
            Logs::add($this->getHost(), $aErrorInfos[2], __FILE__, __LINE__);

            return false;
        }

        try {
            $stmt->bindParam(':SYSNAME', $aInfosSystem['SYSNAME']);
            $stmt->bindParam(':SYSTEMTYP', $aInfosSystem['SYSTEMTYP']);
            $stmt->bindParam(':COLOR', $aInfosSystem['COLOR']);
            $stmt->bindParam(':SYSPTY', $aInfosSystem['SYSPTY']);
            $stmt->bindParam(':NMSYS', $aInfosSystem['NMSYS']);

            if (!$stmt->execute()) {
                $aErrorInfos = $con->errorInfo();
                Logs::add($this->getHost(), $aErrorInfos[2], __FILE__, __LINE__);

                return false;
            }

            return $stmt->closeCursor();
        } catch (\PDOException $e) {
            Logs::add($this->getHost(), $e->getMessage(), __FILE__, __LINE__);

            return false;
        }
    }

    /**
     * @param string $system
     * @param int $priority
     * @return bool
     */
    public function updatePriority($system, $priority)
    {
        $con = $this->getConnexion();

        if (!$con instanceof \PDO) {
            return false;
        }

        $query = 'UPDATE GFPSYSGES.SSYPR1P0 SET SYSPTY = :SYSPTY WHERE NMSYS = :NMSYS';
        $iPriority = (int) $priority;

        if (!$stmt = $con->prepare($query)) {
            $aErrorInfos = $con->errorInfo();
            Logs::add($this->getHost(), $aErrorInfos[2], __FILE__, __LINE__);

            return false;
        }

        try {
            $stmt->bindParam(':SYSPTY', $iPriority);
            $stmt->bindParam(':NMSYS', $system);
            $stmt->execute();

        } catch (\PDOException $e) {
            Logs::add($this->getHost(), $e->getMessage(), __FILE__, __LINE__);

            return false;
        }

        return $stmt->closeCursor();
    }
}