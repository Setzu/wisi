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
                $this->addFlashMessage('Le système : ' . $aPostedDatas['NMSYS'] . ' existe déjà');

                header('Location: /wisi/system');
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
                $this->addFlashMessage("L'un des champs dépasse la longueur maximale autorisée ou n'a pas été renseigné.");
                header('Location: /wisi/system');
                exit;
            }

            if (!isset($aSystemInfos['SYSTEMTYP'])) {
                $aSystemInfos['SYSTEMTYP'] = System::DEFAULT_SYSTEM_TYPE;
            }

            if (!$oConnectionService->addConnection($aPostedDatas)) {
                $this->addFlashMessage("Le système n'a pas pu être ajouté, voir fichier de logs pour plus de détail");

                header('Location: /wisi/system');
                exit;
            } else {
                $this->addFlashMessage('Le système a bien été ajouté', false);
            }

            header('Location: /wisi/accueil');
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

            if (!isset($_POST['bdd']) || !isset($_POST['host']) || !isset($_POST['user']) || !isset($_POST['password'])) {
                echo 'false';

                return false;
            }

            $bCon = ConnectionModel::testConnection($aPostedDatas);

            if ($bCon) {
                echo 'success';

                return true;
            } else {
                echo 'false';

                return true;
            }
        } else {
            header('Location: /wisi/accueil');
            exit;
        }
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function updateAction()
    {
        if (!empty($_POST)) {
            $aPostedDatas = Router::getPostValues();

            if (!isset($aPostedDatas['NMSYS'])) {
                header('Location: /wisi/system/update');
                exit;
            }

            $sSystemName = urldecode($aPostedDatas['NMSYS']);
            $oSystemService = new System();
            $aSystemInfos  = $oSystemService->getInfosSystemByName($sSystemName);

            if (!is_array($aSystemInfos) || count($aSystemInfos) == 0) {
                $this->addFlashMessage('Le système : ' . $sSystemName . ' n\'existe pas');

                header('Location: /wisi/system/update');
                exit;
            }

            $aModif = array_diff($aPostedDatas, $aSystemInfos);

            if (count($aModif) == 0) {
                $this->addFlashMessage("Aucune modification n'a été effectuée");

                header('Location: /wisi/accueil');
                exit;
            }

            if ((isset($aModif['SYSNAME'  ]) && strlen($aPostedDatas['SYSNAME'  ]) > 10) ||
                (isset($aModif['SYSPTY'   ]) && strlen($aPostedDatas['SYSPTY'   ]) > 2 ) ||
                (isset($aModif['SYSTEMTYP']) && strlen($aPostedDatas['SYSTEMTYP']) > 3 ) ||
                (isset($aModif['COLOR'    ]) && strlen($aPostedDatas['COLOR'    ]) > 6 )
            ) {
                $this->addFlashMessage("L'un des champs dépasse la longueur maximale autorisée");

                header('Location: /wisi/system/update');
                exit;
            }

            if (!$oSystemService->updateSystem($aPostedDatas)) {
                $this->addFlashMessage("Le système n'a pas pu être ajouté, voir fichier de logs pour plus de détail");

                header('Location: /wisi/system/update');
                exit;
            } else {
                $this->addFlashMessage('Le système a bien été modifié', false);
            }

            header('Location: /wisi/accueil');
            exit;
        } else {
            $oConnectionService = new Connection();
            $aConnectionsList = $oConnectionService->getAllConnections();

            if (!is_array($aConnectionsList) || count($aConnectionsList) == 0) {
                $this->addFlashMessage('Aucun système n\'a été trouvé, commencez par en ajouter un');

                header('Location: /wisi/accueil');
                exit;
            }

            $this->setVariables(['aSystemsList' => $aConnectionsList]);

            return $this->render('system', 'update');
        }
    }

    /**
     * Method for Ajax context
     *
     * @throws \Exception
     */
    public function deleteAction()
    {
        if (!empty($_POST)) {

            $aPostedDatas = Router::getPostValues();

            if (!isset($aPostedDatas['nmsys']) || strlen($aPostedDatas['nmsys']) > 10 ) {
                echo 'false';
                return false;
            }

            $oConnectionService = new Connection();
            $bConnectionAlreadyExists = $oConnectionService->isConnectionExists($aPostedDatas['nmsys']);

            if (!$bConnectionAlreadyExists) {
                $this->addFlashMessage('Le système : ' . $aPostedDatas['nmsys'] . ' n\'existe pas');

                echo 'false';
                return false;
            }

            $oSystem = new System();

            if (!$oSystem->deleteSystemByName($aPostedDatas['nmsys'])) {
                $this->addFlashMessage('Le système n\'a pas pu être supprimé. Consultez le fichier de logs pour plus de détails');

                echo 'false';
                return false;
            }

            $this->addFlashMessage('Le système a bien été supprimé', false);

            echo 'true';
            return true;
        } else {
            header('Location: /wisi/accueil');
            exit;
        }
    }

    /**
     * Method for Ajax context
     *
     * @throws \Exception
     */
    public function loadInfosAction()
    {
        if (!empty($_POST)) {
            $aPostedDatas = Router::getPostValues();

            if (!isset($aPostedDatas['select'])) {
                header('Location: /wisi/system/update');
                exit;
            }

            $sSystemName = urldecode($aPostedDatas['select']);
            $oConnectionService = new Connection();
            $bSystemexists = $oConnectionService->isConnectionExists($sSystemName);

            if (!$bSystemexists) {
                return null;
            }

            $oSystemService = new System();
            $aInfosSystems = $oSystemService->getInfosSystemByName($sSystemName);

            if (!is_array($aInfosSystems) || count($aInfosSystems) == 0) {
                return null;
            }

            echo json_encode($aInfosSystems);
            return true;
        } else {
            header('Location: /wisi/accueil');
            exit;
        }
    }
}