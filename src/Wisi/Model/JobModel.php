<?php
/**
 * Created by PhpStorm.
 * User: david b. <dav.bla28@gmail.com>
 * Date: 20/02/18
 * Time: 10:44
 */

namespace Wisi\Model;


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
            return [];
        }

        $query = 'SELECT NMSYS, JOBNAME, JOBUSER, JOBNUMBER, JOBTYPE, JOBSUBTYPE, SUBSYSTEM, ACTJOBSTS, PROCESSUNT
FROM GFPSYSGES.SSYJBSP0 WHERE JOBSTATUS = :JOBSTATUS AND JOBUSER != :JOBUSER ORDER BY PROCESSUNT DESC FETCH FIRST 3 ROWS ONLY';

        if (!$stmt = $con->prepare($query)) {
            return null;
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
            // @TODO : créer fichier de logs puis insérer les infos liées à l'erreur de connexion
//            $error = $e->getMessage();

            return [];
        }

        return $aResult;
    }

    /**
     * @TODO : en attente de la création du fichier
     *
     * @param array $aInfosJob
     * @return bool
     */
    public function addJobFollow(array $aInfosJob)
    {
        /*
        $con = $this->getConnexion();

        if (!$con instanceof \PDO) {
            return false;
        }

        $query = 'INSERT INTO GFPSYSGES. () VALUES (:, :, :, :)';

        if (!$stmt = $con->prepare($query)) {
            return false;
        }

        try {
            $stmt->bindParam(':', $aInfosJob['system']);
            $stmt->bindParam(':', $aInfosJob['sub-system']);
            $stmt->bindParam(':', $aInfosJob['name']);
            $stmt->bindParam(':', $aInfosJob['user']);
            $stmt->execute();
            $stmt->closeCursor();
        } catch (\PDOException $e) {
            // @TODO : créer fichier de logs puis insérer les infos liées à l'erreur de connexion
//            $error = $e->getMessage();

            return false;
        }

        return true;
        */
    }
}