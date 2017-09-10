<?php
namespace Application\Factories;

use Application\Controller\ScheduleController;
use Application\Database\ScheduleTable;
use Application\Database\ScheduleRowTable;
use Application\Database\JobTable;
use Application\Database\EmployeeTable;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\Session\SessionManager;

final class ScheduleControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $db = $container->get('config')['db'];
        $sessionManager = $container->get(SessionManager::class);
        $sessionContainer = $container->get('SchedulerContainer');


        $scheduleTable = new ScheduleTable(
            $db['database'],
            $db['username'],
            $db['password'],
            $db['hostname'],
            $db['driver'])
        ;

        $scheduleRowTable = new ScheduleRowTable(
            $db['database'],
            $db['username'],
            $db['password'],
            $db['hostname'],
            $db['driver'])
        ;

        $jobTable = new JobTable(
            $db['database'],
            $db['username'],
            $db['password'],
            $db['hostname'],
            $db['driver'])
        ;

        $employeeTable = new EmployeeTable(
            $db['database'],
            $db['username'],
            $db['password'],
            $db['hostname'],
            $db['driver'])
        ;

        return new ScheduleController($scheduleTable, $scheduleRowTable, $jobTable, $employeeTable, $sessionManager, $sessionContainer);
    }
}
