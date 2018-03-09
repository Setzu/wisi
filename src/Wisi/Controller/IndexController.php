<?php
/**
 * Created by PhpStorm.
 * User: david b. <dav.bla28@gmail.com>
 * Date: 19/02/18
 * Time: 11:33
 */

namespace Wisi\Controller;


use Wisi\Services\Connection;
use Wisi\Services\Job;
use Wisi\Services\Message;
use Wisi\Services\System;

class IndexController extends AbstractController
{

    const ALERT_CPU = 'cpu';
    const ALERT_ASP = 'asp';

    /**
     * @return mixed
     * @throws \Exception
     */
    public function indexAction()
    {
        return $this->render();
    }

    /**
     * Method for Ajax context
     *
     * Récupère la liste des messages QSYSOPR, le pourcentage d'utilisation des disques et diverses infos.
     * Gestion des alertes
     *
     * @return mixed
     * @throws \Exception
     */
    public function messagesAction()
    {
        if (!empty($_POST)) {
            $oConnectionService = new Connection();
            $aConnectionsList = $oConnectionService->getAllConnections();
            $aMainInfos = [];

            // On récupère les messages QSYSOPR ainsi que les infos sur l'utilisation des disques et de l'UC pour chaques systèmes
            foreach ($aConnectionsList as $aSystem) {
                $oMessage = new Message($aSystem);
                $oSystem = new System($aSystem);
                $aMainInfos[$aSystem['NMSYS']]['MESSAGES'] = $oMessage->getMessagesQSYSOPR();
                $aMainInfos[$aSystem['NMSYS']]['UC'] = $oSystem->getUCUtilisation();

                // Ajout d'une alerte si le % d'utilisation de l'UC est supérieur à 80%
//                if (isset($aMainInfos[$aSystem['NMSYS']]['UC']['sASPUtilisation']) && $aMainInfos[$aSystem['NMSYS']]['UC']['sASPUtilisation'] > 80) {
//                    $alert = 'Le système ' . $aSystem['NMSYS'] . ' est à ' . $aMainInfos[$aSystem['NMSYS']]['UC']['sASPUtilisation'] . " % d'utilisation sur l'ASP 1";
//                    $this->addAlert($aSystem['NMSYS'], $aSystem['SYSNAME'], self::ALERT_ASP, $alert);
//                }

                // Ajout d'une alerte si le % d'utilisation du CPU est supérieur à 80%
//                if (isset($aMainInfos[$aSystem['NMSYS']]['UC']['sCPUUtilisation']) && $aMainInfos[$aSystem['NMSYS']]['UC']['sCPUUtilisation'] > 80) {
//                    $alert = 'Le CPU du système ' . $aSystem['NMSYS'] . ' utilise ' . $aMainInfos[$aSystem['NMSYS']]['UC']['sCPUUtilisation'] . ' % de ressources';
//                    $this->addAlert($aSystem['NMSYS'], $aSystem['SYSNAME'], self::ALERT_CPU, $alert);
//                }

                $oJob = new Job($aSystem);
                $aRequiredJobs = $oJob->verifRequiredJobs();

                // Ajout d'une alerte si l'un des job du fichier SSYPR3P0 n'a pas été trouvé dans SSYJBSP0
                if (is_array($aRequiredJobs) && count($aRequiredJobs) > 0) {
                    foreach ($aRequiredJobs as $k => $aJobs) {
                        if (!$aJobs['isExist']) {
                            $alert = 'Le job ' . $aJobs['job']['JOBNAME'] . " de l'utilisateur " . $aJobs['job']['USERNAME'] .
                                ' et du sous-système ' . $aJobs['job']['SUBSYSTEM'] . " n'a pas été trouvé dans le fichier SSYJBSP0";
                            $this->addAlert($aSystem['NMSYS'], $aSystem['SYSNAME'], $k, $alert);
                        }
                    }
                }

                $aMainInfos[$aSystem['NMSYS']]['COLOR'] = $aSystem['COLOR'];
                $aMainInfos[$aSystem['NMSYS']]['SYSNAME'] = $aSystem['SYSNAME'];
                $aMainInfos[$aSystem['NMSYS']]['SYSPTY'] = $aSystem['SYSPTY'];
            }

            $this->setVariables([
                'aConnectionsList' => $aConnectionsList,
                'aMainInfos' => $aMainInfos
            ]);

            return require_once __DIR__ . '/../View/index/messages.php';
        } else {
            header('location: /index');
            exit;
        }
    }

    /**
     * Method for Ajax context
     *
     * @return mixed
     * @throws \Exception
     */
    public function jobsAction()
    {
        if (!empty($_POST)) {
            $oConnectionService = new Connection();
            $aConnectionsList = $oConnectionService->getAllConnections();
            $aJobsList = [];

            // On récupère les 3 jobs les plus gourmands en ressources (hors jobs QSYS) de chaques systèmes
            foreach ($aConnectionsList as $aSystem) {
                $oJob = new Job($aSystem);
                $aJobsList[$aSystem['NMSYS']]['jobs'] = $oJob->getHigherProcessUnitJobs(5);
                $aJobsList[$aSystem['NMSYS']]['SYSNAME'] = $aSystem['SYSNAME'];
                $aJobsList[$aSystem['NMSYS']]['COLOR'] = $aSystem['COLOR'];
                $aJobsList[$aSystem['NMSYS']]['SYSPTY'] = $aSystem['SYSPTY'];
            }

            $this->setVariables([
                'aJobs' => $aJobsList
            ]);

            return require __DIR__ . '/../View/index/jobs.php';
        } else {
            header('location: /index');
            exit;
        }
    }

    /**
     * Method for Ajax context
     *
     * @return void
     * @throws \Exception
     */
    public function statusAction()
    {
        if (!empty($_POST)) {
            $oConnectionService = new Connection();
            $aConnectionsList = $oConnectionService->getAllConnections();
            $aSystemStatus = [];

            // On récupère le statut de chaques systèmes
            foreach ($aConnectionsList as $aSystem) {
                $oSystem = new System($aSystem);
                $aSystemStatus[$aSystem['NMSYS']]['status'] = $oSystem->getSystemStatus();
                $aSystemStatus[$aSystem['NMSYS']]['NMSYS'] = $aSystem['NMSYS'];
                $aSystemStatus[$aSystem['NMSYS']]['SYSNAME'] = $aSystem['SYSNAME'];
            }

            $this->setVariables(['aConnectionsList' => $aConnectionsList]);

            echo json_encode($aSystemStatus);

            return;
        } else {
            header('location: /index');
            exit;
        }
    }
}