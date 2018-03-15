<?php
/**
 * Created by PhpStorm.
 * User: david b. <dav.bla28@gmail.com>
 * Date: 22/02/18
 * Time: 10:47
 */

namespace Wisi\Services;


use Wisi\Model\JobModel;

class Job extends JobModel
{

    /**
     * Job constructor.
     * @param array $aSystemInfos
     * @throws \Exception
     */
    public function __construct(array $aSystemInfos = [])
    {
        parent::__construct($aSystemInfos);
    }

    public function getRequiredJobs($system)
    {
        return parent::selectRequiredJobsBySystem($system);
    }

    /**
     * Si la quantité est égale à 0, on récupère au moins un résultat
     *
     * @param int $quantity
     * @return array
     */
    public function getHigherProcessUnitJobs($quantity)
    {
        $iQuantity = (int) $quantity;

        if ($iQuantity == 0) {
            $iQuantity++;
        }

        return parent::selectHigherProcessUnitJobs($iQuantity);
    }

    /**
     * @param string $firstLetter
     * @param string $secondLetter
     * @return string
     * @throws \Exception
     */
    public static function getJobTypeField($firstLetter, $secondLetter = '')
    {
        if (!file_exists(__DIR__ . '/../../../config/application.php')) {
            throw new \Exception('Le fichier application.php est introuvable');
        }

        $aConfig = require __DIR__ . '/../../../config/application.php';
        $aFieldType = $aConfig['jobs'];

        if (!empty($secondLetter) && $secondLetter != ' ') {
            $key = $firstLetter . $secondLetter;

            if (array_key_exists($key, $aFieldType)) {
                $sTypeField = $aFieldType[$firstLetter . $secondLetter];
            } else {
                $sTypeField = 'ERR';
            }
        } else {
            if (array_key_exists($firstLetter, $aFieldType)) {
                $sTypeField = $aFieldType[$firstLetter];
            } else {
                $sTypeField = 'ERR';
            }
        }

        return $sTypeField;
    }

    /**
     * @param array $aInfosJob
     * @return bool
     */
    public function addJobFollow(array $aInfosJob)
    {
        return parent::insertJobFollow(Utils::decodeUTF($aInfosJob));
    }

    /**
     * @param string $system
     * @return array
     */
    public function getInactiveJobsBySystem($system)
    {
        $aRequiredJobs = $this->selectInactiveJobsBySystem($system);

        return $aRequiredJobs;
    }
}