<?php
/**
 * Created by PhpStorm.
 * User: ANGELA
 * Date: 07/08/2017
 * Time: 11:35 PM
 */

namespace Application\Model;


class Employee
{
    //private $empId;
    private $firstName;
    private $lastName;
    private $email;
    private $phone;

    /**
     * @return mixed
     */
/*    public function getEmpId()
    {
        return $this->empId;
    }*/

    /**
     * @param mixed $empId
     */
    /*public function setEmpId($empId)
    {
        $this->empId = $empId;
    }*/

    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param mixed $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param mixed $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }


}