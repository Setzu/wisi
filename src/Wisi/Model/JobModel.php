<?php
/**
 * Created by PhpStorm.
 * User: david b. <dav.bla28@gmail.com>
 * Date: 20/02/18
 * Time: 10:44
 */

namespace Wisi\Model;


use Wisi\Services\Logs;

class JobModel extends ConnectionModel
{

    const DEFAULT_STATUS = '*ACTIVE';
    const DEFAULT_USER = 'QSYS';

    /**
     * TestModel constructor.
     * @param array $aSystemInfos
     */
    public function __construct(array $aSystemInfos = [])
    {
        parent::__construct($aSystemInfos);
    }

    /**
     * @return array
     */
    public function selectThreeLastJobs()
    {
        $con = $this->getConnexion();

        if (!$con instanceof \PDO) {
            $aErrorInfos = $con->errorInfo();
            Logs::add($aErrorInfos[2] . ' in ' . __FILE__ . ' at line ' . __LINE__);

            return [];
        }

        $query = 'SELECT NMSYS, JOBNAME, JOBUSER, JOBNUMBER, JOBTYPE, JOBSUBTYPE, SUBSYSTEM, ACTJOBSTS, PROCESSUNT
FROM GFPSYSGES.SSYJBSP0 WHERE JOBSTATUS = :JOBSTATUS AND JOBUSER != :JOBUSER ORDER BY PROCESSUNT DESC FETCH FIRST 3 ROWS ONLY';

        if (!$stmt = $con->prepare($query)) {
            $aErrorInfos = $con->errorInfo();
            Logs::add($aErrorInfos[2] . ' in ' . __FILE__ . ' at line ' . __LINE__);

            return [];
        }

        try {
            $sStatus = self::DEFAULT_STATUS;
            $sUser = self::DEFAULT_USER;
            $stmt->bindParam(':JOBSTATUS', $sStatus);
            $stmt->bindParam(':JOBUSER', $sUser);
            $stmt->execute();
            $aResult = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            $stmt->closeCursor();
        } catch (\PDOException $e) {
            Logs::add($e->getMessage() . ' in ' . __FILE__ . ' at line ' . __LINE__);

            return [];
        }

        return $aResult;
    }

    /**
     * @param array $aInfosJob
     * @return bool
     */
    public function insertJobFollow(array $aInfosJob)
    {
        $con = $this->getConnexion();

        if (!$con instanceof \PDO) {
            $aErrorInfos = $con->errorInfo();
            Logs::add($aErrorInfos[2] . ' in ' . __FILE__ . ' at line ' . __LINE__);

            return false;
        }

        $query = 'INSERT INTO GFPSYSGES.SSYPR3P0 (NMSYS, SUBSYSTEM, JOBNAME, USERNAME) VALUES (:NMSYS, :SUBSYSTEM, :JOBNAME, :USERNAME)';

        if (!$stmt = $con->prepare($query)) {
            $aErrorInfos = $con->errorInfo();
            Logs::add($aErrorInfos[2] . ' in ' . __FILE__ . ' at line ' . __LINE__);

            return false;
        }

        try {
            $stmt->bindParam(':NMSYS', $aInfosJob['system']);
            $stmt->bindParam(':SUBSYSTEM', $aInfosJob['sub-system']);
            $stmt->bindParam(':JOBNAME', $aInfosJob['name']);
            $stmt->bindParam(':USERNAME', $aInfosJob['user']);
            $stmt->execute();
            $stmt->closeCursor();
        } catch (\PDOException $e) {
            Logs::add($e->getMessage() . ' in ' . __FILE__ . ' at line ' . __LINE__);

            return false;
        }

        return true;
    }

    /**
     * Select all required jobs
     *
     * @return array
     */
    public function selectRequiredJobs()
    {
        $con = $this->getConnexion();

        if (!$con instanceof \PDO) {
            $aErrorInfos = $con->errorInfo();
            Logs::add($aErrorInfos[2] . ' in ' . __FILE__ . ' at line ' . __LINE__);

            return [];
        }

        $query = 'SELECT NMSYS, SUBSYSTEM, JOBNAME, USERNAME FROM GFPSYSGES.SSYPR3P0';

        if (!$stmt = $con->prepare($query)) {
            $aErrorInfos = $con->errorInfo();
            Logs::add($aErrorInfos[2] . ' in ' . __FILE__ . ' at line ' . __LINE__);

            return [];
        }

        try {
            $stmt->execute();
            $aResult = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            $stmt->closeCursor();
        } catch (\PDOException $e) {
            Logs::add($e->getMessage() . ' in ' . __FILE__ . ' at line ' . __LINE__);

            return [];
        }

        return $aResult;
    }

    /**
     * @param array $aJobInfos
     * @return bool
     */
    public function isJobExists(array $aJobInfos)
    {
        $con = $this->getConnexion();

        if (!$con instanceof \PDO) {
            $aErrorInfos = $con->errorInfo();
            Logs::add($aErrorInfos[2] . ' in ' . __FILE__ . ' at line ' . __LINE__);

            return false;
        }

        $query = 'SELECT count(JOBNAME) FROM GFPSYSGES.SSYJBSP0 
WHERE NMSYS = :NMSYS AND SUBSYSTEM = :SUBSYSTEM AND JOBNAME = :JOBNAME AND JOBUSER = :JOBUSER';

        if (!$stmt = $con->prepare($query)) {
            $aErrorInfos = $con->errorInfo();
            Logs::add($aErrorInfos[2] . ' in ' . __FILE__ . ' at line ' . __LINE__);

            return false;
        }

        try {
            $stmt->bindParam(':NMSYS', $aJobInfos['NMSYS']);
            $stmt->bindParam(':SUBSYSTEM', $aJobInfos['SUBSYSTEM']);
            $stmt->bindParam(':JOBNAME', $aJobInfos['JOBNAME']);
            $stmt->bindParam(':JOBUSER', $aJobInfos['USERNAME']);
            $stmt->execute();
            $bExists = (bool) $stmt->fetch(\PDO::FETCH_COLUMN);
            $stmt->closeCursor();
        } catch (\PDOException $e) {
            Logs::add($e->getMessage() . ' in ' . __FILE__ . ' at line ' . __LINE__);

            return false;
        }

        return $bExists;
    }
}