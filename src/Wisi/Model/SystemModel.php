<?php
/**
 * Created by PhpStorm.
 * User: david b. <dav.bla28@gmail.com>
 * Date: 28/02/18
 * Time: 15:04
 */

namespace Wisi\Model;


use Wisi\Services\Logs;

class SystemModel extends ConnectionModel
{

    const DEFAULT_DESCRIPTION = 'Ajouté depuis wisi';

    /**
     * ParametrageModel constructor.
     * @param array $aSystemInfos
     */
    public function __construct(array $aSystemInfos = [])
    {
        parent::__construct($aSystemInfos);
    }

    /**
     * @return array
     */
    public function selectSystemStatus()
    {
        $con = $this->getConnexion();

        if (!$con instanceof \PDO) {
            $aErrorInfos = $con->errorInfo();
            Logs::add($aErrorInfos[2] . ' in ' . __FILE__ . ' at line ' . __LINE__);

            return [];
        }

        $query = 'SELECT ISRCRUSON, BATJBSWMSG, BATJOBSRUN, BATJBSHRUN, BATJHLDJBQ, BATJBQHLD, BATJBUNAJQ, BATENDWPRT FROM GFPSYSGES.SSYS01P0';

        try {
            if (!$stmt = $con->query($query)) {
                $aErrorInfos = $con->errorInfo();
                Logs::add($aErrorInfos[2] . ' in ' . __FILE__ . ' at line ' . __LINE__);

                return [];
            }

            if (!$stmt->execute()) {
                $aErrorInfos = $con->errorInfo();
                Logs::add($aErrorInfos[2] . ' in ' . __FILE__ . ' at line ' . __LINE__);

                return [];
            }

            $aResults = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            $stmt->closeCursor();
        } catch (\PDOException $e) {
            Logs::add($e->getMessage() . ' in ' . __FILE__ . ' at line ' . __LINE__);

            return [];
        }

        return $aResults;
    }

    /**
     * @return array
     */
    public function selectUCUtilisation()
    {
        $con = $this->getConnexion();

        if (!$con instanceof \PDO) {
            return [];
        }

        $query = 'SELECT PCPROCUNTU, PCSYSASPUS FROM GFPSYSGES.SSYS02P0';

        try {
            if (!$stmt = $con->query($query)) {
                $aErrorInfos = $con->errorInfo();
                Logs::add($aErrorInfos[2] . ' in ' . __FILE__ . ' at line ' . __LINE__);

                return [];
            }

            if (!$stmt->execute()) {
                $aErrorInfos = $con->errorInfo();
                Logs::add($aErrorInfos[2] . ' in ' . __FILE__ . ' at line ' . __LINE__);

                return [];
            }

            $aResults = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            $stmt->closeCursor();
        } catch (\PDOException $e) {
            Logs::add($e->getMessage() . ' in ' . __FILE__ . ' at line ' . __LINE__);


            return [];
        }

        return $aResults;
    }

    /**
     * @param string $sSystemName
     * @return array|bool
     */
    public function selectInfosSystemByName($sSystemName)
    {
        $con = $this->getConnexion();

        if (!$con instanceof \PDO) {
            return false;
        }

        $query = 'SELECT NMSYS, SYSNAME, SYSTEMTYP, COLOR, SYSPTY FROM GFPSYSGES.SSYPR1P0 WHERE NMSYS = :NMSYS';

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

            $aResults = $stmt->fetch(\PDO::FETCH_ASSOC);
            $stmt->closeCursor();
        } catch (\PDOException $e) {
            Logs::add($e->getMessage() . ' in ' . __FILE__ . ' at line ' . __LINE__);


            return false;
        }

        return $aResults;
    }

    public function deleteSystemByName($sSystemName)
    {
        $con = $this->getConnexion();

        if (!$con instanceof \PDO) {
            return false;
        }

        $query = 'DELETE FROM GFPSYSGES.SSYPR1P0 WHERE NMSYS = :NMSYS';

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

            $stmt->closeCursor();
        } catch (\PDOException $e) {
            Logs::add($e->getMessage() . ' in ' . __FILE__ . ' at line ' . __LINE__);


            return false;
        }

        return $stmt->closeCursor();
    }
}