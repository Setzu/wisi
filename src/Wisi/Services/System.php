<?php
/**
 * Created by PhpStorm.
 * User: david b. <dav.bla28@gmail.com>
 * Date: 28/02/18
 * Time: 15:16
 */

namespace Wisi\Services;


use Wisi\Model\SystemModel;

class System extends SystemModel
{

    const DEFAULT_SYSTEM_TYPE = 'DEV';

    /**
     * Systems constructor.
     * @param array $aSystemInfos
     */
    public function __construct(array $aSystemInfos = [])
    {
        parent::__construct($aSystemInfos);
    }

    /**
     * @return array|bool
     */
    public function getSystemStatus()
    {
        return parent::selectSystemStatus();
    }

    /**
     * @return array
     */
    public function getUCUtilisation()
    {
        $aUCUtilisation = parent::selectUCUtilisation();

        if (is_array($aUCUtilisation) && array_key_exists(0, $aUCUtilisation)) {
            $aUCUtilisation['sASPUtilisation'] = number_format(($aUCUtilisation[0]['PCSYSASPUS'] * 100) / $aUCUtilisation[0]['TOTAUXSTG'], 2);
        } else {
            return [];
        }

        return $aUCUtilisation;
    }
}