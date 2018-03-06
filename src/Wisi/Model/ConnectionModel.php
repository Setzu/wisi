<?php
/**
 * Created by PhpStorm.
 * User: david b. <dav.bla28@gmail.com>
 * Date: 02/03/18
 * Time: 13:16
 */

namespace Wisi\Model;


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
        $aParams = 'odbc:DRIVER={IBM i Access ODBC Driver};DATABASE=' . $this->getDatabase() . ';SYSTEM=' .
            $this->getHost() . ';PROTOCOL=TCPIP;UID=' . $this->getUser() . ';PWD= ' . $this->getPassword() . ';NAM=1;';

        try {
            // Limitation à quelques secondes car si, pour une raison ou une autre, la connexion a un système ne se fait
            // pas, l'ajax mettra 30 secondes à rafraichir le contenu de l'index
            set_time_limit(3);
            $this->connexion = new \PDO($aParams, $this->getUser(), $this->getPassword(), [\PDO::ATTR_PERSISTENT => TRUE]);
        } catch (\PDOException $e) {
            // @TODO : créer fichier de logs puis insérer les infos liées à l'erreur de connexion
//            $error = $e->getMessage();

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
//            @TODO : créer fichier de logs puis insérer les infos liées à l'erreur de connexion
//            $con->errorInfo();

            return false;
        }

        $query = 'INSERT INTO GFPSYSGES.SSYPR1P0 (NMSYS, SYSNAME, DESCRIPT, IPADR, NMUSR, PWUSR, DBNAME, SYSTEMTYP, COLOR, SYSPTY)
    VALUES (:NMSYS, :SYSNAME, :DESCRIPT, :IPADR, :NMUSR, :PWUSR, :DBNAME, :SYSTEMTYP, :COLOR, :SYSPTY)';

        if (!$stmt = $con->prepare($query)) {
//            @TODO : créer fichier de logs puis insérer les infos liées à l'erreur de connexion
//            $con->errorInfo();

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
//            @TODO : créer fichier de logs puis insérer les infos liées à l'erreur de connexion
//            $con->errorInfo();

                return false;
            }

            $stmt->closeCursor();
        } catch (\PDOException $e) {
//            @TODO : créer fichier de logs puis insérer les infos liées à l'erreur de connexion
//            $error = $e->getMessage();

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
            return false;
        }

        try {
            $stmt->bindParam(':NMSYS', $sSystemName);

            if (!$stmt->execute()) {
//            @TODO : créer fichier de logs puis insérer les infos liées à l'erreur de connexion
//            $con->errorInfo();

                return false;
            }

            $bExists = (bool) $stmt->fetch(\PDO::FETCH_COLUMN);
            $stmt->closeCursor();
        } catch (\PDOException $e) {
//            @TODO : créer fichier de logs puis insérer les infos liées à l'erreur de connexion
//            $error = $e->getMessage();

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
            return false;
        }

        $query = 'SELECT NMSYS, SYSNAME, IPADR, NMUSR, PWUSR, DBNAME, SYSTEMTYP, COLOR, SYSPTY FROM GFPSYSGES.SSYPR1P0';

        if (!$stmt = $con->prepare($query)) {
            return false;
        }

        try {
            if (!$stmt->execute()) {
//            @TODO : créer fichier de logs puis insérer les infos liées à l'erreur de connexion
//            $con->errorInfo();

                return false;
            }

            $aResults = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            $stmt->closeCursor();
        } catch (\PDOException $e) {
//            @TODO : créer fichier de logs puis insérer les infos liées à l'erreur de connexion
//            $error = $e->getMessage();

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
            // Limitation à quelques secondes car si, pour une raison ou une autre, la connexion a un système ne se fait
            // pas, l'ajax mettra 30 secondes à rafraichir le contenu de l'index
            set_time_limit(2);
            $con = new \PDO($aParams, $aSystemInfos['user'], $aSystemInfos['password'], [\PDO::ATTR_PERSISTENT => TRUE]);

            if (!$con) {
                return false;
            }
        } catch (\PDOException $e) {
            // @TODO : créer fichier de logs puis insérer les infos liées à l'erreur de connexion
//            $error = $e->getMessage();

            return false;
        }

        return true;
    }
}