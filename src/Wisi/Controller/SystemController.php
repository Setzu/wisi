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
use Wisi\Services\System;
use Wisi\Stdlib\Router;

class SystemController extends AbstractController
{

    /**
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
                $this->setFlashMessage('Le système : ' . $aPostedDatas['NMSYS'] . ' existe déjà.');

                header('Location: /system');
                exit;
            }

            if (!$oConnectionService->addConnection($aPostedDatas)) {
                $this->setFlashMessage('Le système n\'a pas pu être ajouté. Consultez le fichier de logs pour plus 
                    de détails.');
            } else {
                $this->setFlashMessage('Le système a bien été ajouté', false);
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
     *
     */
    public function editAction()
    {

    }
}