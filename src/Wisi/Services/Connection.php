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
     * @throws \Exception
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
        $iPriority = (int) $aSystemInfos['SYSPTY'];

        if ($iPriority == 0) {
            Logs::add($aSystemInfos['NMSYS'], 'La priorité doit être supérieur à 0', __FILE__ , __LINE__);

            return false;
        }

        $this->updatePriority($aSystemInfos['NMSYS'], $iPriority);

        $aSystemInfos['PWUSR']  = base64_encode($aSystemInfos['PWUSR']);
        $aSystemInfos['SYSPTY'] = (int) $aSystemInfos['SYSPTY'];

        return parent::insertConnection($aSystemInfos);
    }

    /**
     * @return array
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