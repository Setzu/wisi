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

    const DEFAULT_TIMER = 30;

    /**
     * @return int
     */
    public function getQuantityJobsToDisplay()
    {
        return parent::selectQuantityJobsToDisplay();
    }

    /**
     * @return int
     */
    public function getTimerRefresh()
    {
        $timer = parent::selectTimerRefresh();

        if ($timer <= 0) {
            $timer = self::DEFAULT_TIMER;
        }

        return $timer;
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

        if ($iTimer === 0) {
            Logs::add('Le timer ne peut pas être inférieur à 0 seconde. Timer renseigné : ' . $iTimer . ' in ' . __FILE__ . ' at line ' .__LINE__);

            return false;
        }

        return parent::updateTimerRefresh($timer);
    }
}