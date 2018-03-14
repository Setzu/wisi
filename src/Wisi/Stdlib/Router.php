<?php
/**
 * Created by PhpStorm.
 * User: david b. <david.blanchard@gfpfrance.com>
 * Date: 19/02/18
 * Time: 11:33
 */

namespace Wisi\Stdlib;

use Wisi\Controller\IndexController;

class Router
{
    const DEFAULT_CONTROLLER = 'IndexController';
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
        $aParamsUri = explode('/', $sUri, 3);

        if (isset($aParamsUri[1]) && !empty($aParamsUri[1])) {
            if (preg_match(self::REGEX_DEFAULT, $aParamsUri[1])) {
                $sController = ucfirst(strtolower(trim(htmlspecialchars($aParamsUri[1])))) . 'Controller';
            } else {
                return (new IndexController())->pageNotFound();
            }
        } else {
            $sController = self::DEFAULT_CONTROLLER;
        }

        $sControllerName = 'Wisi\\Controller\\' . $sController;

        if (!class_exists($sControllerName)) {
            return (new IndexController())->pageNotFound();
        }

        if (isset($aParamsUri[2]) && !empty($aParamsUri[2])) {
            if (preg_match(self::REGEX_DEFAULT, $aParamsUri[2])) {
                $sActionName = ucfirst(strtolower(trim(htmlspecialchars($aParamsUri[2])))) . 'Action';
            } else {
                return (new IndexController())->pageNotFound();
            }
        } else {
            $sActionName = self::DEFAULT_ACTION;
        }

        if (!method_exists($sControllerName, $sActionName)) {
            return (new IndexController())->pageNotFound();
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
        $aParamsUri = explode('/', $sUri, 3);

        if (isset($aParamsUri[3]) && !empty($aParamsUri[3]) && preg_match(self::REGEX_PARAMS, $aParamsUri[3])) {
            return $aParamsUri[3];
        } else {
            return null;
        }
    }
}