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

            if (!array_key_exists('system', $aPostedDatas) || empty($aPostedDatas['system']) ||
                !array_key_exists('sub-system', $aPostedDatas) || empty($aPostedDatas['sub-system']) ||
                !array_key_exists('name', $aPostedDatas) || empty($aPostedDatas['name']) ||
                !array_key_exists('user', $aPostedDatas) || empty($aPostedDatas['user'])
            ) {
                $this->setFlashMessage('Veuillez renseigner TOUS les champs');

                header('Location: /job');
                exit;
            } elseif (!in_array($aPostedDatas['system'], $aSystemsList)) {
                $this->setFlashMessage('Le système choisi n\'est pas référencé');

                header('Location: /job');
                exit;
            }

            $oJob = new Job();
            $bReturn = $oJob->addJobFollow($aPostedDatas);

            if ($bReturn) {
                $this->setFlashMessage('Le suivi du job a été ajouté avec succès. Une alerte sera ajoutée dans l\'onglet
                 Alerte si le job ne figure pas dans le fichier SSYJBSP0.', false);

                header('Location: /index');
                exit;
            } else {
                // @TODO: ajouter appel à fonction d'enregistrement de log
                $this->setFlashMessage('Le suivi du job n\'a pas pu être ajouté. Consultez le fichier de logs pour
                plus de détails.');

                header('Location: /job');
                exit;
            }
        }

        $this->setVariables(['aSystems' => $aSystemsList]);
        $this->render('job', 'index');
    }

    /**
     * @throws \Exception
     */
    public function affichageAction()
    {
        $this->render('job', 'affichage');
    }
}