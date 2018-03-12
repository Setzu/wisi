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
     * @throws \Exception
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
            $aUCUtilisation['sASPUtilisation'] = number_format($aUCUtilisation[0]['PCSYSASPUS'] / 10000, 2);
            $aUCUtilisation['sCPUUtilisation'] = number_format($aUCUtilisation[0]['PCPROCUNTU'] / 10, 2);
        } else {
            return [];
        }

        return $aUCUtilisation;
    }

    /**
     * @param $UCValue
     * @return string
     */
    public static function storageClassByUC($UCValue)
    {
        $iUCValue = (int) $UCValue;
        $class = 'success';

        if ($iUCValue >= 50 && $iUCValue < 80) {
            $class = 'warning';
        } elseif ($iUCValue >= 80) {
            $class = 'danger';
        }

        return $class;
    }

    /**
     * @param string $sSystemName
     * @return array
     */
    public function getInfosSystemByName($sSystemName)
    {
        if (!is_string($sSystemName) || empty($sSystemName)) {
            Logs::add('Le paramètre doit être une chaine de caractère. Voir ' . __FILE__ . ' at line ' . __LINE__);
            return [];
        }

        $mReturn = parent::selectInfosSystemByName($sSystemName);

        if (is_array($mReturn)) {
            return array_map('trim', $mReturn);
        }

        return [];
    }

    /**
     * @param array $aInfosSystem
     * @return bool
     */
    public function updateSystem(array $aInfosSystem)
    {
        return parent::updateSystem($aInfosSystem);
    }

    public function deleteSystemByName($sSystemName)
    {
        if (!is_string($sSystemName) || empty($sSystemName)) {
            Logs::add('Le paramètre doit être un chaine de caractère. Voir ' . __FILE__ . ' at line ' . __LINE__);

            return [];
        }

        return parent::deleteSystemByName($sSystemName);
    }
}