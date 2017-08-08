<?php
/**
 * Created by PhpStorm.
 * User: ANGELA
 * Date: 07/08/2017
 * Time: 2:56 PM
 */

namespace Application\Model;


class Schedule implements ArrayForDatabase
{
    //need a set version number option????

    private $jobNumber;
    private $versionNum;
    //private $modifiedDate;

    /**
     * @return mixed
     */
    public function getJobNumber()
    {
        return $this->jobNumber;
    }

    /**
     * @param mixed $jobNumber
     */
    public function setJobNumber($jobNumber)
    {
        $this->jobNumber = $jobNumber;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getVersionNum()
    {
        return $this->versionNum;
    }

    /**
     * @param mixed $versionNum
     */
    public function setVersionNum($versionNum)
    {
        $this->versionNum = $versionNum;
        return $this;
    }

    /**
     * @return mixed
     */
    /*public function getModifiedDate()
    {
        return $this->modifiedDate;
    }*/

    /**
     * @param mixed $modifiedDate
     */
    /*public function setModifiedDate($modifiedDate)
    {
        $this->modifiedDate = $modifiedDate;
        return $this;
    }*/


    public function getArrayForDatabase() {
        return [
            'job_id' => $this->getJobNumber(),
            'version_num' => $this->getVersionNum()
        ];
    }
}