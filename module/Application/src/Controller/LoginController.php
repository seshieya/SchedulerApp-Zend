<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Zend\Session\Container;
use Zend\Session\SessionManager;
use Zend\Authentication\Result;

use Application\Database\EmployeeTable;
use Application\Database\LoginTable;
use Application\Model\Employee;


class LoginController extends AbstractActionController
{
    private $employeeTable;
    private $loginTable;
    private $sessionManager;
    private $sessionContainer;

    private $authManager;

    private $authService;


    public function __construct(EmployeeTable $employeeTable, LoginTable $loginTable, $sessionManager, $sessionContainer, $authManager, $authService)
    {

        $this->employeeTable = $employeeTable;
        $this->loginTable = $loginTable;

        $this->sessionManager = $sessionManager;
        $this->sessionContainer = $sessionContainer;

        /*$this->sessionManager = new SessionManager();
        $this->sessionContainer = new Container('schedulerContainer', $this->sessionManager);*/

        $this->authManager = $authManager;
        $this->authService = $authService;

    }

    public function loginAction()
    {
        if($this->authManager->getIdentity() != null) {
            $this->redirect()->toRoute('create');
        }
        return new ViewModel();
    }


    public function loginVerifyAction()
    {

        $username = $this->getRequest()->getPost('username');
        $password = $this->getRequest()->getPost('password');

        $dbPassword = $this->loginTable->getPassword($username);

        $result = $this->authManager->login($username, $password, $dbPassword);

        if($result->getCode() == Result::SUCCESS) {
            //get employee details from database:
            $empId = $this->loginTable->getEmpId($username);

            $empDetails = $this->employeeTable->getEmployeeDetails($empId);
            $employeeModel = new Employee();
            $employeeModel
                ->setFirstName($empDetails['first_name'])
                ->setLastName($empDetails['last_name'])
                ->setEmail($empDetails['email'])
                ->setPhone($empDetails['phone']);
            $empData = $employeeModel->getArrayForView();

            $this->sessionContainer->coordinatorName = $empData['full_name'];
            $this->sessionContainer->coordinatorEmail = $empData['email'];
            $this->sessionContainer->coordinatorPhone = $empData['phone'];

            //unset($sessionContainer->username);

            $this->redirect()->toRoute('create');

        }

        return new ViewModel();
    }

    public function logoutAction()
    {

        //$this->sessionManager->destroy();

        $this->authManager->logout();

        $this->redirect()->toRoute('login');
    }


}
