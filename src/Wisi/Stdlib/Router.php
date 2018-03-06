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

    /**
     * @return mixed
     * @throws \Exception
     */
    public static function dispatch()
    {
        if (!empty($_GET['controller'])) {
            $sController = ucfirst(strtolower(trim(htmlspecialchars($_GET['controller'])))) . 'Controller';
        } else {
            $sController = self::DEFAULT_CONTROLLER;
        }

        $sControllerName = 'Wisi\\Controller\\' . $sController;

        if (!class_exists($sControllerName)) {
            return (new IndexController())->pageNotFound();
        }

        if (!empty($_GET['action'])) {
            $sActionName = ucfirst(strtolower(trim(htmlspecialchars($_GET['action'])))) . 'Action';
        } else {
            $sActionName = self::DEFAULT_ACTION;
        }

        if (!method_exists($sControllerName, $sActionName)) {
            return (new IndexController())->pageNotFound();
        }

        return (new $sControllerName)->$sActionName();
    }

    public static function getPostValues()
    {
        $values = [];

        foreach ($_POST as $k => $v) {
            $values[$k] = htmlspecialchars(trim($v));
        }

        return $values;
    }

    public static function getGetValues()
    {
        return htmlspecialchars($_GET['param']);
    }
}