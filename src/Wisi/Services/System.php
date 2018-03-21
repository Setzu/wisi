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
     * @return float
     */
    public function getUCUtilisation()
    {
        $iUCUtilisation = parent::selectUCUtilisation();

        if ($iUCUtilisation > 0) {
            $iUCUtilisation = $iUCUtilisation / 10;
        }

        return $iUCUtilisation;
    }

    /**
     * @return array
     */
    public function getASPUtilisation()
    {
        $aASPUtilisation = parent::selectASPUtilisation();
        $aASPUtilisationFormated = [];

        if (is_array($aASPUtilisation) && count($aASPUtilisation) > 0) {
            foreach ($aASPUtilisation as $asp => $aValues) {
                $aASPUtilisationFormated[$aValues['ASPNUMBER']] = number_format($aValues['OCCUPATION'] * 100 / $aValues['DSKCAPTY'], 2);
            }
        }

        return $aASPUtilisationFormated;
    }

    /**
     * @return array
     */
    public function getASPStorage()
    {
        $aASPStorage = parent::selectASPUtilisation();

        if (is_array($aASPStorage) && count($aASPStorage) > 0) {
            foreach ($aASPStorage as $asp => $aValues) {
                $aASPStorage[$asp]['DSKCAPTY'] = number_format($aValues['DSKCAPTY'] / 1000, 0, ',', '');
                $aASPStorage[$asp]['DSKSTGAVA'] = number_format($aValues['DSKSTGAVA'] / 1000, 0, ',', '');
                $aASPStorage[$asp]['USED'] = $aASPStorage[$asp]['DSKCAPTY'] - $aASPStorage[$asp]['DSKSTGAVA'];
            }
        }

        return $aASPStorage;
    }

    /**
     * @param int $UCValue
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
            Logs::add($this->getHost(), 'Le paramètre doit être une chaine de caractère', __FILE__, __LINE__);
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

    /**
     * @param string $sSystemName
     * @return array|bool
     */
    public function deleteSystemByName($sSystemName)
    {
        if (!is_string($sSystemName) || empty($sSystemName)) {
            Logs::add($this->getHost(), 'Le paramètre doit être un chaine de caractère', __FILE__, __LINE__);

            return [];
        }

        return parent::deleteSystemByName($sSystemName);
    }
}