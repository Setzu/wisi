<?php
/**
 * Created by PhpStorm.
 * User: david b. <david.blanchard@gfpfrance.com>
 * Date: 19/02/18
 * Time: 11:33
 */

namespace Wisi\Stdlib;

use Wisi\Controller\AccueilController;

class Router
{
    const DEFAULT_CONTROLLER = 'AccueilController';
    const DEFAULT_ACTION = 'indexAction';
    const REGEX_DEFAULT = '/^[a-zA-Z_-]*$/';
    const REGEX_PARAMS = '/^[a-zA-Z0-9/\ $=_£%:\-+]*$/';

    public $param;

    /**
     * @return mixed
     * @throws \Exception
     */
    public static function dispatch()
    {
        $sUri = $_SERVER['REQUEST_URI'];
        $aParamsUri = explode('/', $sUri, 4);

        if (isset($aParamsUri[2]) && !empty($aParamsUri[2])) {
            if (preg_match(self::REGEX_DEFAULT, $aParamsUri[2])) {
                $sController = ucfirst(strtolower(trim(htmlspecialchars($aParamsUri[2])))) . 'Controller';
            } else {
                return (new AccueilController())->pageNotFound();
            }
        } else {
            $sController = self::DEFAULT_CONTROLLER;
        }

        $sControllerName = 'Wisi\\Controller\\' . $sController;

        if (!class_exists($sControllerName)) {
            return (new AccueilController())->pageNotFound();
        }

        if (isset($aParamsUri[3]) && !empty($aParamsUri[3])) {
            if (preg_match(self::REGEX_DEFAULT, $aParamsUri[3])) {
                $sActionName = ucfirst(strtolower(trim(htmlspecialchars($aParamsUri[3])))) . 'Action';
            } else {
                return (new AccueilController())->pageNotFound();
            }
        } else {
            $sActionName = self::DEFAULT_ACTION;
        }

        if (!method_exists($sControllerName, $sActionName)) {
            return (new AccueilController())->pageNotFound();
        }

        return (new $sControllerName)->$sActionName();
    }

    /**
     * @return array
     */
    public static function getPostValues()
    {
        $values = [];

        foreach ($_POST as $k => $v) {
            $values[$k] = htmlspecialchars(trim($v));
        }

        return $values;
    }

    /**
     * @return string|null
     */
    public static function getParams()
    {
        $sUri = $_SERVER['REQUEST_URI'];
        $aParamsUri = explode('/', $sUri, 4);

        if (isset($aParamsUri[4]) && !empty($aParamsUri[4]) && preg_match(self::REGEX_PARAMS, $aParamsUri[4])) {
            return $aParamsUri[4];
        } else {
            return null;
        }
    }
}