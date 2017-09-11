<?php
namespace Application\Service\Factories;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

use Application\Service\AuthAdapter;
use Application\Database\EmployeeTable;
use Application\Database\LoginTable;

/**
 * This is the factory class for AuthAdapter service. The purpose of the factory
 * is to instantiate the service and pass it dependencies (inject dependencies).
 */
class AuthAdapterFactory implements FactoryInterface
{
    /**
     * This method creates the AuthAdapter service and returns its instance. 
     */
    /*public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        // Get Doctrine entity manager from Service Manager.
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        // Create the AuthAdapter and inject dependency to its constructor.
        return new AuthAdapter($entityManager);
    }*/

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /*$db = $container->get('config')['db'];

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
        ;*/

        return new AuthAdapter();

    }
}
