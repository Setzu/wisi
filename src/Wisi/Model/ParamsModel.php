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
     * Selection de tous les messages QSYSOPR excepté ceux concernant les imprimantes
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
            Logs::add('Host ' . $this->getHost() . ' ' . $aErrorInfos[2] . ' in ' . __FILE__ . ' at line ' . __LINE__);

            return 0;
        }

        $iId = 1;

        try {
            $stmt->bindParam(':ID', $iId);
            $stmt->execute();
            $iResult = $stmt->fetchColumn();
            $stmt->closeCursor();
        } catch (\PDOException $e) {
            Logs::add($e->getMessage() . ' in ' . __FILE__ . ' at line ' . __LINE__);

            return 0;
        }

        return $iResult;
    }

    /**
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
            Logs::add($aErrorInfos[2] . ' in ' . __FILE__ . ' at line ' . __LINE__);

            return false;
        }

        try {
            $stmt->bindParam(':JOB_TO_DISPLAY', $quantity);
            $stmt->bindParam(':ID', $iId);

            if (!$stmt->execute()) {
                $aErrorInfos = $con->errorInfo();
                Logs::add('Host ' . $this->getHost() . ' ' . $aErrorInfos[2] . ' in ' . __FILE__ . ' at line ' . __LINE__);

                return false;
            }

            return $stmt->closeCursor();
        } catch (\PDOException $e) {
            Logs::add($e->getMessage() . ' in ' . __FILE__ . ' at line ' . __LINE__);

            return false;
        }
    }

    /**
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
            Logs::add($aErrorInfos[2] . ' in ' . __FILE__ . ' at line ' . __LINE__);

            return false;
        }

        try {
            $stmt->bindParam(':TIMER_REFRESH', $timer);

            if (!$stmt->execute()) {
                $aErrorInfos = $con->errorInfo();
                Logs::add('Host ' . $this->getHost() . ' ' . $aErrorInfos[2] . ' in ' . __FILE__ . ' at line ' . __LINE__);

                return false;
            }

            return $stmt->closeCursor();
        } catch (\PDOException $e) {
            Logs::add($e->getMessage() . ' in ' . __FILE__ . ' at line ' . __LINE__);

            return false;
        }
    }

}