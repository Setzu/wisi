<?php
/**
 * Created by PhpStorm.
 * User: david b. <david.blanchard@gfpfrance.com>
 * Date: 19/02/18
 * Time: 11:33
 */

namespace Wisi\Controller;

use Wisi\Stdlib\Layout;
use Wisi\Stdlib\SessionManager;

abstract class AbstractController extends SessionManager
{

    const DEFAULT_DIRECTORY = 'accueil';
    const DEFAULT_VIEW = 'index';

    abstract function indexAction();

    /**
     * Create properties for each values of $aVariables table
     *
     * @param array $aVariables
     * @return $this
     * @throws \Exception
     */
    protected function setVariables(array $aVariables)
    {
        foreach ($aVariables as $sName => $mValue) {
            if (!is_string($sName)) {
                throw new \Exception('The key must be a string.');
            }

            $this->{$sName} = $mValue;
        }

        return $this;
    }

    /**
     * Display view
     *
     * @param string $directory
     * @param string $view
     * @return mixed
     * @throws \Exception
     */
    protected function render($directory = '', $view = '', $loadLayout = true)
    {
        if (empty($directory) || !is_string($directory)) {
            $directory = self::DEFAULT_DIRECTORY;
        }

        if (empty($view) || !is_string($view)) {
            $view = self::DEFAULT_VIEW;
        }

        $sFilePath = __DIR__ . '/../View/' . $directory . '/' . $view . '.php';

        // Contrôle de l'existence du fichier
        if (file_exists($sFilePath)) {
            if ($loadLayout) {
                $oLayout = new Layout();

                $this->setVariables([
                    'content' => $sFilePath
                ]);

                return require_once $oLayout->getLayout();
            }

            return require_once $sFilePath;
        } else {
            throw new \Exception('Le fichier ' . $sFilePath . ' n\'a pas été trouvé.');
        }
    }


    /**
     * @return mixed
     * @throws \Exception
     */
    public function pageNotFound()
    {
        if (!file_exists(__DIR__ . '/../View/error/404.php')) {
            throw new \Exception('The 404 view does not found.');
        }

        return $this->render('error', '404');
    }
}