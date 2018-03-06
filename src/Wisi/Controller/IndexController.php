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
     * @return mixed
     * @throws \Exception
     */
    public function messagesAction()
    {
        if (!empty($_POST)) {
            $oConnectionService = new Connection();
            $aConnectionsList = $oConnectionService->getAllConnections();
            $aMessagesList = $aUCList = [];

            foreach ($aConnectionsList as $aSystem) {
                $oMessage = new Message($aSystem);
                $aMessagesList[$aSystem['NMSYS']]['messages'] = $oMessage->getMessagesQSYSOPR();
                $aMessagesList[$aSystem['NMSYS']]['COLOR'] = $aSystem['COLOR'];
                $aMessagesList[$aSystem['NMSYS']]['SYSNAME'] = $aSystem['SYSNAME'];
                $aMessagesList[$aSystem['NMSYS']]['SYSPTY'] = $aSystem['SYSPTY'];

                $oSystem = new System($aSystem);
                $aUCList[$aSystem['NMSYS']] = $oSystem->getUCUtilisation();
                $aUCList[$aSystem['NMSYS']]['SYSNAME'] = $aSystem['SYSNAME'];
            }

            $this->setVariables([
                'aConnectionsList' => $aConnectionsList,
                'aMessagesList' => $aMessagesList,
                'aUCList' => $aUCList
            ]);

            return require __DIR__ . '/../View/index/messages.php';

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

            foreach ($aConnectionsList as $aSystem) {
                $oJob = new Job($aSystem);
                $aJobsList[$aSystem['NMSYS']]['jobs'] = $oJob->getThreeLastJobs();
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

            foreach ($aConnectionsList as $aSystem) {
                $oSystem = new System($aSystem);
                $aSystemStatus[$aSystem['NMSYS']]['status'] = $oSystem->getSystemStatus();
                $aSystemStatus[$aSystem['NMSYS']]['NMSYS'] = $aSystem['NMSYS'];
                $aSystemStatus[$aSystem['NMSYS']]['SYSNAME'] = $aSystem['SYSNAME'];
                $aSystemStatus[$aSystem['NMSYS']]['SYSPTY'] = $aSystem['SYSPTY'];
            }

            $this->setVariables(['aConnectionsList' =>$aConnectionsList]);

            echo json_encode($aSystemStatus);

            return;
        } else {
            header('location: /index');
            exit;
        }
    }

    /**
     * @TODO : à terminer
     * Method for Ajax context
     *
     * @throws \Exception
     */
    public function alertAction()
    {
        if (!empty($_POST)) {
            $oConnectionService = new Connection();
            $aConnectionsList = $oConnectionService->getAllConnections();
            $aSystemStatus = [];

            foreach ($aConnectionsList as $aSystem) {
                $oSystem = new System($aSystem);
                $aSystemStatus[$aSystem['NMSYS']]['status'] = $oSystem->getSystemStatus();
                $aSystemStatus[$aSystem['NMSYS']]['NMSYS'] = $aSystem['NMSYS'];
                $aSystemStatus[$aSystem['NMSYS']]['SYSNAME'] = $aSystem['SYSNAME'];
                $aSystemStatus[$aSystem['NMSYS']]['SYSPTY'] = $aSystem['SYSPTY'];
            }

            $this->setVariables(['aConnectionsList' =>$aConnectionsList]);

            echo json_encode($aSystemStatus);

            return;
        } else {
            header('location: /index');
            exit;
        }
    }
}