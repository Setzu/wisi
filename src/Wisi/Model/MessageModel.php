<?php
/**
 * Created by PhpStorm.
 * User: david b. <dav.bla28@gmail.com>
 * Date: 22/02/18
 * Time: 15:02
 */

namespace Wisi\Model;


use Wisi\Services\Logs;

class MessageModel extends ConnectionModel
{

    const PRINTER_USER = 'QSPLJOB';

    /**
     * MessagesModel constructor.
     * @param array $aSystemInfos
     * @throws \Exception
     */
    public function __construct(array $aSystemInfos = [])
    {
        parent::__construct($aSystemInfos);
    }

    /**
     * Selection de tous les messages QSYSOPR excepté ceux concernant les imprimantes
     *
     * @return array
     */
    public function selectMessagesQSYSOPR()
    {
        $con = $this->getConnexion();

        if (!$con instanceof \PDO) {
            return [];
        }

        $query = 'SELECT * FROM GFPSYSGES.SSYQSOP0 WHERE FROMUSER != :FROMUSER';

        if (!$stmt = $con->prepare($query)) {
            $aErrorInfos = $con->errorInfo();
            Logs::add($this->getHost(), $aErrorInfos[2], __FILE__, __LINE__);

            return [];
        }

        $sFromUser = self::PRINTER_USER;

        try {
            $stmt->bindParam(':FROMUSER', $sFromUser);
            $stmt->execute();
            $aResult = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            $stmt->closeCursor();
        } catch (\PDOException $e) {
            Logs::add($this->getHost(), $e->getMessage(), __FILE__, __LINE__);

            return [];
        }

        return $aResult;
    }
}