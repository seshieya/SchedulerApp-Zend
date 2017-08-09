<?php
/**
 * Created by PhpStorm.
 * User: ANGELA
 * Date: 08/08/2017
 * Time: 7:20 PM
 */

namespace Application\Model;

class Login implements ArrayForDatabase
{
    private $loginId;
    private $username;
    private $password;
    private $empId;

    /**
     * @return mixed
     */
    public function getLoginId()
    {
        return $this->loginId;
    }

    /**
     * @param mixed $loginId
     */
    public function setLoginId($loginId)
    {
        $this->loginId = $loginId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEmpId()
    {
        return $this->empId;
    }

    /**
     * @param mixed $empId
     */
    public function setEmpId($empId)
    {
        $this->empId = $empId;
        return $this;
    }

    public function getArrayForDatabase()
    {
        return [
            'username' => $this->getUsername(),
            'password' => $this->getPassword(),
            'emp_id' => $this->getEmpId()
        ];
    }

}