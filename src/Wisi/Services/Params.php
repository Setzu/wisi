<?php
/**
 * Created by PhpStorm.
 * User: david b. <dav.bla28@gmail.com>
 * Date: 14/03/18
 * Time: 16:06
 */

namespace Wisi\Services;


use Wisi\Model\ParamsModel;

class Params extends ParamsModel
{

    /**
     * @return int
     */
    public function getQuantityJobsToDisplay()
    {
        return parent::selectQuantityJobsToDisplay();
    }

    /**
     * @param int $quantity
     * @return bool
     */
    public function updateJobsToDisplay($quantity)
    {
        $iQuantity = (int) $quantity;

        if ($iQuantity === 0) {
            Logs::add('La quantité de jobs à afficher doit être supérieur à 0 in ' . __FILE__ . ' at line ' .__LINE__);

            return false;
        }

        return parent::updateJobsToDisplay($quantity);
    }

    /**
     * @param int $timer
     * @return bool
     */
    public function updateTimerRefresh($timer)
    {
        $iTimer = (int) $timer;

        if ($iTimer <= 30) {
            Logs::add('Le timer ne peut pas être inférieur à 30 secondes. Timer renseigné : ' . $iTimer . ' in ' . __FILE__ . ' at line ' .__LINE__);

            return false;
        }

        return parent::updateTimerRefresh($timer);
    }
}