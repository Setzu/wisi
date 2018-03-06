<?php
/**
 * Created by PhpStorm.
 * User: david b. <david.blanchard@gfpfrance.com>
 * Date: 07/02/18
 * Time: 18:36
 */

namespace Wisi\Stdlib;


class Layout
{

    private $layout;

    public function __construct()
    {
        $this->setLayout(__DIR__ . '/../View/layout/layout.php');
    }

    /**
     * @return mixed
     */
    public function getLayout()
    {
        return $this->layout;
    }

    /**
     * @param mixed $layout
     */
    public function setLayout($layout)
    {
        $this->layout = $layout;
    }
}