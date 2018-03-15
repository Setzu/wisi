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

    /**
     * @param string $date
     * @return string
     */
    public static function formatDateToEU($date = '')
    {
        if (empty($date)) {
            return date('d/m/Y H:i:s');
        }

        $oDate = DateTime::createFromFormat('Y-m-d-H.i.s', substr($date, 0, 19));

        if (!$oDate) {
            Logs::add('La date ' . $date . ' n\'est pas au bon format. Voir ' . __FILE__ . ' à la ligne ' . __LINE__);

            return $date;
        }

        return $oDate->format('d/m/Y H:i:s');
    }

    /**
     * @param $mData
     * @return array|string
     */
    public static function decodeUTF($mData)
    {
        if (is_array($mData)) {
            foreach ($mData as $k => $infos) {
                if (is_string($infos)) {
                    $aDecodedDatas[$k] = utf8_decode($infos);
                }
            }

            return $aDecodedDatas;
        } elseif (is_string($mData)) {
            return utf8_decode($mData);
        } else {
            return $mData;
        }
    }
}