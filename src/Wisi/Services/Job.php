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
     */
    public function __construct(array $aSystemInfos = [])
    {
        parent::__construct($aSystemInfos);
    }

    /**
     * @return array
     */
    public function getThreeLastJobs()
    {
        return parent::selectThreeLastJobs();
    }

    /**
     * @param string $firstLetter
     * @param string $secondLetter
     * @return string
     */
    public static function getJobTypeField($firstLetter, $secondLetter = '')
    {
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
        return parent::insertJobFollow($aInfosJob);
    }

    /**
     * @return array
     */
    public function verifRequiredJobs()
    {
        $aRequiredJobs = $this->selectRequiredJobs();

        if (count($aRequiredJobs) == 0) {
            return [];
        }

        $aListFindJobs = [];

        foreach ($aRequiredJobs as $k => $aJobs) {
            $aListFindJobs[$k]['isExist'] = $this->isJobExists($aJobs);
            $aListFindJobs[$k]['job'] = $aJobs;
        }

        return $aListFindJobs;
    }
}