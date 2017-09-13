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
    //todo Angela need a set version number option????

    private $jobNumber;
    private $versionNum;
    private $modifiedDate;
    private $jobAddress;
    private $jobAccess;

    public function reset() {
        $this->jobNumber = 0;
        $this->versionNum = 0;
        $this->modifiedDate = '';
        $this->jobAddress = '';
        $this->jobAccess = '';
    }


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
    public function getModifiedDate()
    {
        return $this->modifiedDate;
    }

    /**
     * @param mixed $modifiedDate
     */
    public function setModifiedDate($modifiedDate)
    {
        $this->modifiedDate = $modifiedDate;
        return $this;
    }


    /**
     * @return mixed
     */
    public function getJobAddress()
    {
        return $this->jobAddress;
    }

    /**
     * @param mixed $jobAddress
     */
    public function setJobAddress($jobAddress)
    {
        $this->jobAddress = $jobAddress;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getJobAccess()
    {
        return $this->jobAccess;
    }

    /**
     * @param mixed $jobAccess
     */
    public function setJobAccess($jobAccess)
    {
        $this->jobAccess = $jobAccess;
        return $this;
    }





    public function getArrayForDatabase() {
        return [
            'job_id' => $this->getJobNumber(),
            'version_num' => $this->getVersionNum()
        ];
    }

    public function getArrayForView() {
        return [
            'job_id' => $this->getJobNumber(),
            'version_num' => $this->getVersionNum(),
            'modified_date' => $this->getModifiedDate(),
            'address' => $this->getJobAddress(),
            'access' => $this->getJobAccess()
        ];
    }
}