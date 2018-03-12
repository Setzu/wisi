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
     * @return array
     */
    public function selectMessagesQSYSOPR()
    {
        $con = $this->getConnexion();

        if (!$con instanceof \PDO) {
            $aErrorInfos = $con->errorInfo();
            Logs::add('Host ' . $this->getHost() . ' ' . $aErrorInfos[2] . ' in ' . __FILE__ . ' at line ' . __LINE__);

            return [];
        }

        $query = 'SELECT * FROM GFPSYSGES.SSYQSOP0';

        if (!$stmt = $con->prepare($query)) {
            $aErrorInfos = $con->errorInfo();
            Logs::add('Host ' . $this->getHost() . ' ' . $aErrorInfos[2] . ' in ' . __FILE__ . ' at line ' . __LINE__);

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

}