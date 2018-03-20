<?php
/**
 * Created by PhpStorm.
 * User: david b. <dav.bla28@gmail.com>
 * Date: 14/03/18
 * Time: 16:00
 */

namespace Wisi\Model;


use Wisi\Services\Logs;

class ParamsModel extends ConnectionModel
{

    /**
     * Récupère le nombre de jobs à afficher dans l'onglet Jobs
     *
     * @return int
     */
    public function selectQuantityJobsToDisplay()
    {
        $con = $this->getConnexion();

        if (!$con instanceof \PDO) {
            return 0;
        }

        $query = 'SELECT JOB_TO_DISPLAY FROM GFPSYSGES.SSYPR4P0 WHERE ID = :ID';

        if (!$stmt = $con->prepare($query)) {
            $aErrorInfos = $con->errorInfo();
            Logs::add($this->getHost(), $aErrorInfos[2], __FILE__, __LINE__);

            return 0;
        }

        $iId = 1;

        try {
            $stmt->bindParam(':ID', $iId);
            $stmt->execute();
            $iResult = $stmt->fetchColumn();
            $stmt->closeCursor();
        } catch (\PDOException $e) {
            Logs::add($this->getHost(), $e->getMessage(), __FILE__, __LINE__);

            return 0;
        }

        return $iResult;
    }

    /**
     * Récupère le timer de rafraichissement de l'application
     *
     * @return int
     */
    public function selectTimerRefresh()
    {
        $con = $this->getConnexion();

        if (!$con instanceof \PDO) {
            return 0;
        }

        $query = 'SELECT TIMER_REFRESH FROM GFPSYSGES.SSYPR4P0 WHERE ID = :ID';

        if (!$stmt = $con->prepare($query)) {
            $aErrorInfos = $con->errorInfo();
            Logs::add($this->getHost(), $aErrorInfos[2], __FILE__, __LINE__);

            return 0;
        }

        $iId = 1;

        try {
            $stmt->bindParam(':ID', $iId);
            $stmt->execute();
            $iResult = $stmt->fetchColumn();
            $stmt->closeCursor();
        } catch (\PDOException $e) {
            Logs::add($this->getHost(), $e->getMessage(), __FILE__, __LINE__);

            return 0;
        }

        return $iResult;
    }

    /**
     * Met à jour le nombre de jobs à afficher dans l'onglet Jobs

     * @param int $quantity
     * @return bool
     */
    public function updateJobsToDisplay($quantity)
    {
        $con = $this->getConnexion();

        if (!$con instanceof \PDO) {
            return false;
        }

        $query = 'UPDATE GFPSYSGES.SSYPR4P0 SET JOB_TO_DISPLAY = :JOB_TO_DISPLAY WHERE ID = :ID';
        $iId = 1;

        if (!$stmt = $con->prepare($query)) {
            $stmt->bindParam(':ID', $iId);
            $aErrorInfos = $con->errorInfo();
            Logs::add($this->getHost(), $aErrorInfos[2], __FILE__, __LINE__);

            return false;
        }

        try {
            $stmt->bindParam(':JOB_TO_DISPLAY', $quantity);
            $stmt->bindParam(':ID', $iId);

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
     * Met à jour le timer de rafraichissement de l'application
     *
     * @param int $timer
     * @return bool
     */
    public function updateTimerRefresh($timer)
    {
        $con = $this->getConnexion();

        if (!$con instanceof \PDO) {
            return false;
        }

        $query = 'UPDATE GFPSYSGES.SSYPR4P0 SET TIMER_REFRESH = :TIMER_REFRESH';

        if (!$stmt = $con->prepare($query)) {
            $aErrorInfos = $con->errorInfo();
            Logs::add($this->getHost(), $aErrorInfos[2], __FILE__, __LINE__);

            return false;
        }

        try {
            $stmt->bindParam(':TIMER_REFRESH', $timer);

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

}