<?php
/**
 * Created by PhpStorm.
 * User: david b. <dav.bla28@gmail.com>
 * Date: 28/02/18
 * Time: 12:04
 */

namespace Wisi\Controller;


use Wisi\Model\AbstractModel;
use Wisi\Model\ConnectionModel;
use Wisi\Services\Connection;
use Wisi\Services\Logs;
use Wisi\Services\System;
use Wisi\Stdlib\Router;

class SystemController extends AbstractController
{

    /**
     * Add systems method
     *
     * @return mixed
     * @throws \Exception
     */
    public function indexAction()
    {
        if (!empty($_POST)) {
            $aPostedDatas = Router::getPostValues();
            $oConnectionService = new Connection();
            $bConnectionAlreadyExists = $oConnectionService->isConnectionExists($aPostedDatas['NMSYS']);

            if ($bConnectionAlreadyExists) {
                $this->addFlashMessage('Le système : ' . $aPostedDatas['NMSYS'] . ' existe déjà.');

                header('Location: /system');
                exit;
            }

            if (!isset($aPostedDatas['NMSYS'    ]) || strlen($aPostedDatas['NMSYS'    ]) > 10 ||
                !isset($aPostedDatas['SYSNAME'  ]) || strlen($aPostedDatas['SYSNAME'  ]) > 10 ||
                !isset($aPostedDatas['IPADR'    ]) || strlen($aPostedDatas['IPADR'    ]) > 15 ||
                !isset($aPostedDatas['NMUSR'    ]) || strlen($aPostedDatas['NMUSR'    ]) > 10 ||
                !isset($aPostedDatas['PWUSR'    ]) || strlen($aPostedDatas['PWUSR'    ]) > 10 ||
                !isset($aPostedDatas['DBNAME'   ]) || strlen($aPostedDatas['DBNAME'   ]) > 20 ||
                !isset($aPostedDatas['SYSPTY'   ]) || strlen($aPostedDatas['SYSPTY'   ]) > 2  ||
                (isset($aPostedDatas['SYSTEMTYP']) && strlen($aPostedDatas['SYSTEMTYP']) > 3) ||
                (isset($aPostedDatas['COLOR'    ]) && strlen($aPostedDatas['COLOR'    ]) > 6)
            ) {
                $this->addFlashMessage("L'un des champs dépasse la longueur autorisé ou n'a pas été renseigné.");
                header('Location: /system');
                exit;
            }

            if (!isset($aSystemInfos['SYSTEMTYP'])) {
                $aSystemInfos['SYSTEMTYP'] = System::DEFAULT_SYSTEM_TYPE;
            }

            if (!$oConnectionService->addConnection($aPostedDatas)) {
                $this->addFlashMessage("Le système n'a pas pu être ajouté, voir fichier de logs pour plus de détail");
                header('Location: /jobs');
                exit;
            } else {
                $this->addFlashMessage('Le système a bien été ajouté', false);
            }

            header('Location: /index');
            exit;
        } else {
            return $this->render('system');
        }
    }

    /**
     * Method for Ajax context
     *
     * @return bool
     */
    public function testConnectionAction()
    {
        if (!empty($_POST) && array_key_exists('ajax', $_POST) && $_POST['ajax']) {
            $aPostedDatas = Router::getPostValues();
            $bCon = ConnectionModel::testConnection($aPostedDatas);

            if ($bCon) {
                echo 'success';

                return true;
            } else {
                echo 'false';

                return true;
            }
        } else {
            header('Location: /index');
            exit;
        }
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function editAction()
    {
        if (!empty($_POST)) {
            $aPostedDatas = Router::getPostValues();
            $oConnectionService = new Connection();
            $bConnectionAlreadyExists = $oConnectionService->isConnectionExists($aPostedDatas['NMSYS']);

            if ($bConnectionAlreadyExists) {
                $this->addFlashMessage('Le système : ' . $aPostedDatas['NMSYS'] . ' existe déjà.');

                header('Location: /system');
                exit;
            }

            if (!$oConnectionService->addConnection($aPostedDatas)) {
                $this->addFlashMessage('Le système n\'a pas pu être ajouté. Consultez le fichier de logs pour plus 
                    de détails.');
            } else {
                $this->addFlashMessage('Le système a bien été ajouté', false);
            }

            header('Location: /index');
            exit;
        } else {
            return $this->render('system');
        }
    }
}