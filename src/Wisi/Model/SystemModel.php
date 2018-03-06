<?php
/**
 * Created by PhpStorm.
 * User: david b. <dav.bla28@gmail.com>
 * Date: 28/02/18
 * Time: 15:04
 */

namespace Wisi\Model;


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
            return [];
        }

        $query = 'SELECT ISRCRUSON, BATJBSWMSG, BATJOBSRUN, BATJBSHRUN, BATJHLDJBQ, BATJBQHLD, BATJBUNAJQ, BATENDWPRT FROM GFPSYSGES.SSYS01P0';

        try {
            if (!$stmt = $con->query($query)) {
//            @TODO : créer fichier de logs puis insérer les infos liées à l'erreur de connexion
//            $con->errorInfo();

                return [];
            }

            if (!$stmt->execute()) {
//            @TODO : créer fichier de logs puis insérer les infos liées à l'erreur de connexion
//            $con->errorInfo();

                return [];
            }

            $aResults = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            $stmt->closeCursor();
        } catch (\PDOException $e) {
//            @TODO : créer fichier de logs puis insérer les infos liées à l'erreur de connexion
//            $error = $e->getMessage();

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

        $query = 'SELECT PCPROCUNTU, PCSYSASPUS, TOTAUXSTG FROM GFPSYSGES.SSYS02P0';

        try {
            if (!$stmt = $con->query($query)) {
//            @TODO : créer fichier de logs puis insérer les infos liées à l'erreur de connexion
//            $con->errorInfo();

                return [];
            }

            if (!$stmt->execute()) {
//            @TODO : créer fichier de logs puis insérer les infos liées à l'erreur de connexion
//            $con->errorInfo();

                return [];
            }

            $aResults = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            $stmt->closeCursor();
        } catch (\PDOException $e) {
//            @TODO : créer fichier de logs puis insérer les infos liées à l'erreur de connexion
//            $error = $e->getMessage();

            return [];
        }

        return $aResults;
    }
}