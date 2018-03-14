<?php
/**
 * Created by PhpStorm.
 * User: david b.
 * Date: 02/06/16
 * Time: 10:14
 */

namespace Wisi\Stdlib;


abstract class SessionManager
{

    public $aFlashMessages = [];
    public $alertsCount = 0;

    const DEFAULT_EXPIRATION_TIME = 60;
    const FLASH_MESSAGE = 'flashmessage';
    const ALERTS = 'alerts';
    const DANGER = 'danger';
    const SUCCESS = 'success';
    const ICON_DANGER = 'glyphicon-remove';
    const ICON_SUCCESS = 'glyphicon-ok';

    /**
     * SessionManager constructor.
     */
    public function __construct()
    {
        self::startSession();
    }

    /**
     * Démarre la session
     *
     * @param int $expiration
     */
    public static function startSession($expiration = self::DEFAULT_EXPIRATION_TIME)
    {
        $exp = (int) $expiration;

        if (session_status() == PHP_SESSION_NONE || session_status() != PHP_SESSION_ACTIVE) {
            session_start();
            session_cache_expire($exp > 0 ? $exp : self::DEFAULT_EXPIRATION_TIME);
        }
    }

    /**
     * Récupère la session
     *
     * @return array
     */
    public function getSession()
    {
        return $_SESSION;
    }

    /**
     * Détruit la clé $key de la session
     *
     * @param $key
     * @return $this
     */
    public function destroySessionValue($key)
    {
        if (is_string($key) || is_int($key)) {
            if (array_key_exists($key, $_SESSION)) {
                unset($_SESSION[$key]);
            }
        }

        return $this;
    }

    /**
     * Enregistre les flash messages en session
     *
     * @param $message
     * @param bool|true $error
     * @throws \Exception
     */
    public function addFlashMessage($message, $error = true)
    {
        if ($error) {
            $type = self::DANGER;
            $icon = self::ICON_DANGER;
        } else {
            $type = self::SUCCESS;
            $icon = self::ICON_SUCCESS;
        }

        $_SESSION[self::FLASH_MESSAGE][$type] = [
            'message' => $message,
            'icon' => $icon
        ];
    }

    /**
     * Affiche les flash messages et les supprimes de la session
     *
     * @return string
     */
    public static function flashMessages()
    {
        $aSession = $_SESSION;
        $sFlashMessages = '';

        if (array_key_exists(self::FLASH_MESSAGE, $aSession)) {
            foreach ($aSession[self::FLASH_MESSAGE] as $type => $aContent) {
                $sFlashMessages .= "<div class='alert alert-$type'><span class='glyphicon " . $aContent['icon'] .
                    "' aria-hidden='true'></span>&nbsp;&nbsp;&nbsp;" . $aContent['message'] . '</div><br>';
            }
        }

        unset($_SESSION[self::FLASH_MESSAGE]);

        return $sFlashMessages;
    }

    /**
     * Ajoute des alertes pour le $system
     * Veillez à ce que l'$id soit unique afin de s'assurer d'avoir plusieurs alertes sur un même système
     *
     * @param string $system
     * @param string $alias
     * @param string|int $id
     * @param string $alert
     */
    public function addAlert($system, $alias, $id, $alert)
    {
        $this->alertsCount ++;
        $_SESSION[self::ALERTS][$system][$id]['alert'] = $alert;
        $_SESSION[self::ALERTS][$system][$id]['alias'] = $alias;
        $_SESSION[self::ALERTS]['count'] = $this->alertsCount;
    }

    /**
     * Affiche toutes les alertes
     */
    public static function alerts()
    {
        $sAlerts = '';

        if (isset($_SESSION[self::ALERTS])) {
            $sAlerts = "<table class='table'><thead><tr><th>Système</th><th>Alias</th><th>Alerte</th></tr></thead><tbody>";

            foreach ($_SESSION[self::ALERTS] as $system => $v) {
                if (is_array($v)) {
                    foreach($v as $title => $aValues) {
                        $sAlerts .= '<tr><td>' . $system . '</td><td>' . $aValues['alias'] . '</td><td>' . $aValues['alert'] . '</td></tr>';
                    }
                }
            }

            $sAlerts .= "</tbody></table>";
        }

        unset($_SESSION[self::ALERTS]);

        return $sAlerts;
    }
}
