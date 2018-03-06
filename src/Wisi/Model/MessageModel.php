<?php
/**
 * Created by PhpStorm.
 * User: david b. <dav.bla28@gmail.com>
 * Date: 22/02/18
 * Time: 15:02
 */

namespace Wisi\Model;


class MessageModel extends ConnectionModel
{

    /**
     * MessagesModel constructor.
     * @param array $aSystemInfos
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
            // @TODO : créer fichier de logs puis insérer les infos liées à l'erreur de connexion
//            $con->errorInfo();

            return [];
        }

        $query = 'SELECT * FROM GFPSYSGES.SSYQSOP0 WHERE MESSAGESTP > :MESSAGESTP';
//        $query = 'SELECT * FROM GFPSYSGES.MSGQSOP0 WHERE MESSAGESTP > :MESSAGESTP';

        if (!$stmt = $con->prepare($query)) {
            // @TODO : créer fichier de logs puis insérer les infos liées à l'erreur de connexion
//            $con->errorInfo();

            return [];
        }

        // @TODO : à dégager
        $sTime = '2018-02-22';

        try {
            $stmt->bindParam(':MESSAGESTP', $sTime);
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

}