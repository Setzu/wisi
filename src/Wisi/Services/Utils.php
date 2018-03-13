<?php
/**
 * Created by PhpStorm.
 * User: david b. <dav.bla28@gmail.com>
 * Date: 12/03/18
 * Time: 12:14
 */

namespace Wisi\Services;


use DateTime;

class Utils
{

    public static function formatDateToEU($date)
    {
        $oDate = DateTime::createFromFormat('Y-m-d-H.i.s', substr($date, 0, 19));

        if (!$oDate) {
            Logs::add('La date ' . $date . ' n\'est pas au bon format. Voir ' . __FILE__ . ' à la ligne ' . __LINE__);

            return $date;
        }

        return $oDate->format('d/m/Y H:i:s');
    }

}