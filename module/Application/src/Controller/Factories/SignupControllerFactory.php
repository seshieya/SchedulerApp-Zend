<?php
namespace Application\Controller\Factories;

use Application\Controller\SignupController;
use Application\Database\LoginTable;
use Application\Database\EmployeeTable;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

final class SignupControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $db = $container->get('config')['db'];

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

        return new SignupController($employeeTable, $loginTable);

    }
}
