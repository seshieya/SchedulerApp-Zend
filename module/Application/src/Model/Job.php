<?php
/**
 * Created by PhpStorm.
 * User: ANGELA
 * Date: 06/08/2017
 * Time: 11:40 PM
 */

namespace Application\Model;


class Job
{
    private $jobNumber;
    private $empId;
    private $address;
    private $access;

    /**
     * @param $jobNumber
     */
    public function setJobNumber($jobNumber) {
        if(strlen($jobNumber) >= 8 && strlen($jobNumber) <= 11) {
            $this->jobNumber = $jobNumber;
        }
    }

    /**
     * @return mixed
     */
    public function getJobNumber() {
        return $this->jobNumber;
    }

    /**
     * @param $empId
     */
    public function setEmpId($empId) {
        if($empId > 0) {
            $this->empId = $empId;
        }
    }

    /**
     * @return mixed
     */
    public function getEmpId() {
        return $this->empId;
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param mixed $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @return mixed
     */
    public function getAccess()
    {
        return $this->access;
    }

    /**
     * @param mixed $access
     */
    public function setAccess($access)
    {
        $this->access = $access;
    }



}