<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Zend\Crypt\Password\Bcrypt;

use Application\Database\EmployeeTable;
use Application\Database\LoginTable;
use Application\Model\Employee;
use Application\Model\Login;

class SignupController extends AbstractActionController
{
    private $employeeTable;
    private $loginTable;

    public function __construct(EmployeeTable $employeeTable, LoginTable $loginTable)
    {
        $this->employeeTable = $employeeTable;
        $this->loginTable = $loginTable;
    }


    public function signupAction()
    {
        return new ViewModel();
    }

    public function saveSignupAction()
    {
        $request = $this->getRequest();

        if($request->isPost()) {

            $post = $request->getPost();

            $employeeArray = [];
            $loginArray = [];

            $employeeModel = new Employee();
            $loginModel = new Login();

            $employeeModel
                ->setFirstName($post['firstname'])
                ->setLastName($post['lastname'])
                ->setEmail($post['email'])
                ->setPhone($post['phone']);
            $employeeArray = $employeeModel->getArrayForDatabase();
            $this->employeeTable->insertEmployeeData($employeeArray);


            $bcrypt = new Bcrypt();
            $passwordHash = $bcrypt->create($post['password']);
            $employeeId = $this->employeeTable->getLastEmployeeId();
            $loginModel
                ->setUsername($post['username'])
                ->setPassword($passwordHash)
                ->setEmpId($employeeId);
            $loginArray = $loginModel->getArrayForDatabase();
            $this->loginTable->insertLoginData($loginArray);




            /*foreach($post as $key => $value) {
                if($key == 'username') {
                    $loginArray[$key] = $value;
                }
                else if($key == 'password') {
                    $passwordHash = $bcrypt->create($value);
                    $loginArray[$key] = $passwordHash;
                }
                else {
                    $employeeArray[$key] = $value;
                }
            }*/
        }

        return new ViewModel();
    }


}
