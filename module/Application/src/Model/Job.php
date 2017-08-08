<?php
/**
 * Created by PhpStorm.
 * User: ANGELA
 * Date: 06/08/2017
 * Time: 11:40 PM
 */

namespace Application\Model;


class Job implements ArrayForDatabase
{
    private $jobNumber;
    private $empId;
    private $address;
    private $access;
    private $jobNumberFormatted;

    public function reset() {
        $this->jobNumber = 0;
        $this->empId = 0;
        $this->address = '';
        $this->access = '';
        $this->jobNumberFormatted = 0;
    }

    /**
     * @param $jobNumber
     */
    public function setJobNumber($jobNumber) {
        $this->jobNumber = $jobNumber;
        return $this;
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
        $this->empId = $empId;
        return $this;
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
        return $this;
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
        return $this;
    }

    /**
     * @param $jobNumber
     */
    public function setJobNumberFormatted($jobNumber) {
        if(strlen($jobNumber) == 9) {
            $this->jobNumber = preg_replace('/(\d{4})(\d{5})/', '$1-$2', $jobNumber);
        }
        return $this;
    }

    /**
     * @return mixed
     */
    public function getJobNumberFormatted() {
        return $this->jobNumberFormatted;
    }

    public function getArrayForDatabase() {
        return [
            'job_id' => $this->getJobNumber(),
            'emp_id' => $this->getEmpId(),
            'address' => $this->getAddress(),
            'access' => $this->getAccess()
        ];
    }

    public function getArrayForView() {
        return [
            'job_id' => $this->getJobNumberFormatted(),
            'emp_id' => $this->getEmpId(),
            'address' => $this->getAddress(),
            'access' => $this->getAccess()
        ];
    }



}