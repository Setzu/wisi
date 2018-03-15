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
use Wisi\Services\Params;
use Wisi\Services\System;
use Wisi\Stdlib\Router;

class IndexController extends AbstractController
{

    const ALERT_CPU = 'cpu';
    const ALERT_ASP = 'asp';
    const DEFAULT_QUANTITY = 3;

    /**
     * @return mixed
     * @throws \Exception
     */
    public function indexAction()
    {
        $oServiceParams = new Params();

        $this->setVariables(['iTimer' => $oServiceParams->getTimerRefresh()]);

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
            $aMainInfos = $aPing = [];

            // On récupère les messages QSYSOPR ainsi que les infos sur l'utilisation des disques et de l'UC pour chaques systèmes
            foreach ($aConnectionsList as $aSystem) {
                $oMessage = new Message($aSystem);
                $oSystem = new System($aSystem);
                $aMainInfos[$aSystem['NMSYS']]['MESSAGES'] = $oMessage->getMessagesQSYSOPR();
                $aMainInfos[$aSystem['NMSYS']]['UC'] = $oSystem->getUCUtilisation();

                $aMainInfos[$aSystem['NMSYS']]['ASP'] = $oSystem->getASPUtilisation();

                // On récupère la liste des jobs obligatoires dans le fichier SSYPR3P0 (uniquement présent sur la DEV)
                $oJob = new Job();
                $aRequiredJobs = $oJob->getRequiredJobsBySystem($aSystem['NMSYS']);

                // Ajout d'une alerte si l'un des job du fichier SSYPR3P0 n'a pas été trouvé dans SSYJBSP0
                if (is_array($aRequiredJobs) && count($aRequiredJobs) > 0) {
                    $oJob = new Job($aSystem);
                    $aRunningJobs = $oJob->verifRequiredJobs($aRequiredJobs);

                    if (is_array($aRunningJobs) && count($aRunningJobs) > 0) {
                        foreach ($aRunningJobs as $k => $aJobs) {
                            if (!$aJobs['isExist']) {
                                $alert = 'Le job ' . $aJobs['job']['JOBNAME'] . " de l'utilisateur " . $aJobs['job']['USERNAME'] .
                                    ' et du sous-système ' . $aJobs['job']['SUBSYSTEM'] . " n'a pas été trouvé dans le fichier SSYJBSP0";
                                $this->addAlert($aSystem['NMSYS'], $aSystem['SYSNAME'], $k, $alert);
                            }
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
            $oServiceParams = new Params();
            $iQuantityJobsToDisplay = $oServiceParams->getQuantityJobsToDisplay();

            if ($iQuantityJobsToDisplay == 0) {
                $iQuantityJobsToDisplay = self::DEFAULT_QUANTITY;
            }

            // On récupère les 3 jobs les plus gourmands en ressources (hors jobs QSYS) de chaque système
            foreach ($aConnectionsList as $aSystem) {
                $oJob = new Job($aSystem);
                $aJobsList[$aSystem['NMSYS']]['jobs'] = $oJob->getHigherProcessUnitJobs($iQuantityJobsToDisplay);
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

            // On récupère le statut de chaque système
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

    /**
     * @return mixed
     * @throws \Exception
     */
    public function timerAction()
    {
        $oParamsService = new Params();

        if (!empty($_POST)) {

            $aPostedData = Router::getPostValues();

            if (!isset($aPostedData['timer']) && !is_numeric($aPostedData['timer']) || $aPostedData['timer'] < 10 ||
                $aPostedData['timer'] > 90) {
                $this->addFlashMessage('Le timer doit être supérieur à 10 secondes et inférieur à 90 secondes');

                header('Location: /index/timer');
                exit;
            }

            $oParamsService->updateTimerRefresh($aPostedData['timer']);
            $this->addFlashMessage('Le timer de rafraichissement a bien été mis à jour', false);

            header('Location: /index');
            exit;
        } else {
            $this->setVariables(['iTimer' => $oParamsService->getTimerRefresh()]);

            return $this->render('index', 'timer');
        }
    }
}