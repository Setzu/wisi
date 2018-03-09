<?php
/**
 * Created by PhpStorm.
 * User: david b. <dav.bla28@gmail.com>
 * Date: 28/02/18
 * Time: 09:24
 */

namespace Wisi\Controller;


use Wisi\Services\Connection;
use Wisi\Services\Job;
use Wisi\Stdlib\Router;

/**
 * Paramétrage des jobs
 *
 * Class JobController
 * @package Wisi\Controller
 */
class JobController extends AbstractController
{

    /**
     * Suivi des jobs
     *
     * @throws \Exception
     */
    public function indexAction()
    {
        $oConnection = new Connection();
        $aConnectionsList = $oConnection->getAllConnections();
        $aSystemsList = [];

        foreach ($aConnectionsList as $k => $aSystem) {
            $aSystemsList[$k] = $aSystem['NMSYS'];
        }

        if (!empty($_POST)) {

            $aPostedDatas = Router::getPostValues();

            // On ne devrait jamais rentrer dans ce if, sauf si un user s'amuse à modifier le contenu html
            if (!isset($aPostedDatas['system'    ]) || strlen($aPostedDatas['system'    ]) > 10 ||
                !isset($aPostedDatas['sub-system']) || strlen($aPostedDatas['sub-system']) > 10 ||
                !isset($aPostedDatas['name'      ]) || strlen($aPostedDatas['name'      ]) > 10 ||
                !isset($aPostedDatas['user'      ]) || strlen($aPostedDatas['user'      ]) > 10
            ) {
                $this->addFlashMessage('Un ou plusieurs champs dépassent la longueur maximale autorisé ou n\'ont pas été renseignés');

                header('Location: /jobs');
                exit;
            } elseif (!in_array($aPostedDatas['system'], $aSystemsList)) {
                $this->addFlashMessage('Le système choisi n\'est pas référencé');

                header('Location: /job');
                exit;
            }

            $oJob = new Job();
            $bReturn = $oJob->addJobFollow($aPostedDatas);

            if ($bReturn) {
                $this->addFlashMessage('Le suivi du job a été ajouté avec succès. Une alerte sera ajoutée dans l\'onglet
                 Alertes si le job ne figure pas dans le fichier SSYJBSP0.', false);

                header('Location: /index');
                exit;
            } else {
                $this->addFlashMessage('Le suivi du job n\'a pas pu être ajouté. Consultez le fichier de logs pour
                plus de détails.');

                header('Location: /job');
                exit;
            }
        }

        $this->setVariables(['aSystems' => $aSystemsList]);
        $this->render('job', 'index');
    }
}