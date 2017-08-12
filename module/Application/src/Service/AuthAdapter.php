<?php

/**
 * Created by PhpStorm.
 * User: ANGELA
 * Date: 10/08/2017
 * Time: 10:20 PM
 */

namespace Application\Service;

use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Authentication\Result;
use Zend\Crypt\Password\Bcrypt;


use Application\Database\LoginTable;

/**
 * Adapter used for authenticating user. It takes login and password on input
 * and checks the database if there is a user with such login (email) and password.
 * If such user exists, the service returns his identity (email). The identity
 * is saved to session and can be retrieved later with Identity view helper provided
 * by ZF3.
 */

class AuthAdapter implements AdapterInterface
{
    /**
     * Username.
     * @var string
     */
    private $username;

    /**
     * Password
     * @var string
     */
    private $password;


    private $loginTable;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->loginTable = new LoginTable('scheduler', 'root', '');
    }

    /**
     * Sets employee email.
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * Sets password.
     */
    public function setPassword($password)
    {
        $this->password = (string)$password;
    }

    /**
     * Performs an authentication attempt.
     */
    public function authenticate()
    {
        // Check the database if there is a password associated with username.
        $empPasswordHash = $this->loginTable->getPassword($this->username);

        // If there is no such user, return 'Identity Not Found' status.
        if ($empPasswordHash == null) {
            return new Result(
                Result::FAILURE_IDENTITY_NOT_FOUND,
                null,
                ['Invalid credentials.']);
        }

        // Now we need to calculate hash based on user-entered password and compare
        // it with the password hash stored in database.
        $bcrypt = new Bcrypt();

        if ($bcrypt->verify($this->password, $empPasswordHash)) {
            // Great! The password hash matches. Return user identity (email) to be
            // saved in session for later use.
            return new Result(
                Result::SUCCESS,
                $this->email,
                ['Authenticated successfully.']);
        }

        // If password check didn't pass return 'Invalid Credential' failure status.
        return new Result(
            Result::FAILURE_CREDENTIAL_INVALID,
            null,
            ['Invalid credentials.']);
    }

}