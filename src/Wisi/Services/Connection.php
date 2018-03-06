<?php
/**
 * Created by PhpStorm.
 * User: david b. <dav.bla28@gmail.com>
 * Date: 02/03/18
 * Time: 13:16
 */

namespace Wisi\Services;


use Wisi\Model\ConnectionModel;

class Connection extends ConnectionModel
{

    const DEFAULT_SYSTEM_TYPE = 'DEV';

    /**
     * Connection constructor.
     * @param array $aSystemInfos
     */
    public function __construct(array $aSystemInfos = [])
    {
        parent::__construct($aSystemInfos);
    }

    /**
     * @param array $aSystemInfos
     * @return bool
     */
    public function addConnection(array $aSystemInfos)
    {
        if (!array_key_exists('NMSYS',     $aSystemInfos) || $aSystemInfos['NMSYS']     > 10 ||
            !array_key_exists('SYSNAME',   $aSystemInfos) || $aSystemInfos['SYSNAME']   > 10 ||
            !array_key_exists('IPADR',     $aSystemInfos) || $aSystemInfos['IPADR']     > 15 ||
            !array_key_exists('NMUSR',     $aSystemInfos) || $aSystemInfos['NMUSR']     > 10 ||
            !array_key_exists('PWUSR',     $aSystemInfos) || $aSystemInfos['PWUSR']     > 10 ||
            !array_key_exists('DBNAME',    $aSystemInfos) || $aSystemInfos['DBNAME']    > 20 ||
            !array_key_exists('SYSPTY',    $aSystemInfos) || $aSystemInfos['SYSPTY']    > 2  ||
            (array_key_exists('SYSTEMTYP', $aSystemInfos) && $aSystemInfos['SYSTEMTYP'] > 3) ||
            (array_key_exists('COLOR',     $aSystemInfos) && $aSystemInfos['COLOR']     > 6)) {

            return false;
        }

        if (!array_key_exists('SYSTEMTYP', $aSystemInfos) || empty($aSystemInfos['SYSTEMTYP'])) {
            $aSystemInfos['SYSTEMTYP'] = self::DEFAULT_SYSTEM_TYPE;
        }

        // En cas d'ajout d'une authentification :
//        $aSystemInfos['PWUSR'] = password_hash($aSystemInfos['PWUSR'], PASSWORD_BCRYPT);
        // Temporairement on utilise un encodage pas très secure...
        $aSystemInfos['PWUSR']  = base64_encode($aSystemInfos['PWUSR']);
        $aSystemInfos['SYSPTY'] = (int) $aSystemInfos['SYSPTY'];

        return parent::insertConnection($aSystemInfos);
    }

    /**
     * @return array|bool
     */
    public function getAllConnections()
    {
        $aSystems = parent::selectAllConnections();

        if (is_array($aSystems) && count($aSystems) > 0) {

            foreach ($aSystems as $k => $aSystem) {
                $aSystems[$k] = array_map('trim', $aSystem);
            }
        }

        return $aSystems;
    }

    /**
     * @param string $sSystemName
     * @return bool
     */
    public function isConnectionExists($sSystemName)
    {
        return parent::isConnectionExists($sSystemName);
    }
}