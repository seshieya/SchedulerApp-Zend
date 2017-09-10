<?php
namespace Application\Factories;

use Application\Controller\LoginController;
use Application\Database\LoginTable;
use Application\Database\EmployeeTable;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\Session\SessionManager;

final class LoginControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $db = $container->get('config')['db'];

        // Retrieve an instance of the session manager from the service manager.
        $sessionManager = $container->get(SessionManager::class);


        $sessionContainer = $container->get('SchedulerContainer');

        $employeeTable = new EmployeeTable(
            $db['database'],
            $db['username'],
            $db['password'],
            $db['hostname'],
            $db['driver'])
        ;

        $loginTable = new LoginTable(
            $db['database'],
            $db['username'],
            $db['password'],
            $db['hostname'],
            $db['driver'])
        ;

        return new LoginController($employeeTable, $loginTable, $sessionManager, $sessionContainer);
    }
}
