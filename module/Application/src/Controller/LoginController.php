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

use Zend\Authentication\Adapter\DbTable\CredentialTreatmentAdapter as AuthAdapter;
use Zend\Db\Adapter\Adapter;
use Zend\Authentication\AuthenticationService;
use Zend\Session\Container;
use Zend\Session\SessionManager;

use Application\Database\EmployeeTable;
use Application\Database\LoginTable;
use Application\Model\Employee;
use Application\Model\Login;

class LoginController extends AbstractActionController
{
    private $employeeTable;
    private $loginTable;

    public function __construct() {
        $this->employeeTable = new EmployeeTable('scheduler', 'root', '');
        $this->loginTable = new LoginTable('scheduler', 'root', '');
    }

    public function loginAction()
    {
        return new ViewModel();
    }

    public function loginVerifyAction()
    {
        $username = $this->getRequest()->getPost('username');
        $password = $this->getRequest()->getPost('password');
        $passwordHashFromDb = $this->loginTable->getPassword($username);

        $bcrypt = new Bcrypt();

        if($bcrypt->verify($password, $passwordHashFromDb)) {
            $this->redirect()->toRoute('home');

            $container = new Container();
            $sessionManager = $container->get(SessionManager::class);

            /*$dbAdapter = new Adapter([
                'driver' => 'Pdo_Mysql',
                'hostname' => 'localhost',
                'username' => 'root',
                'password' => '',
                'database' => 'scheduler',
            ]);
            $authAdapter = new AuthAdapter($dbAdapter);
            $authAdapter
                ->setTableName('login')
                ->setIdentityColumn('username')
                ->setCredentialColumn('password');

            $authAdapter
                ->setIdentity($username)
                ->setCredential($passwordHashFromDb);

            $result = $authAdapter->authenticate();*/
        }

        return new ViewModel();
    }

    public function signupAction()
    {
        return new ViewModel();
    }

    public function saveSignupAction()
    {
        $post = $this->getRequest()->getPost();

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



        return new ViewModel();
    }
}