<?php

namespace Application\Database;

use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter;

class BaseTable
{
    protected $sql;
    protected $adapter;

    /**
     * Table constructor.
     *
     * @param string $hostname
     * @param $database
     * @param $username
     * @param $password
     * @param string $driver
     */
    public function __construct($database, $username, $password, $hostname = '127.0.0.1',  $driver = 'Pdo_Mysql')
    {
        $this->adapter = new Adapter([
            'driver'   => $driver,
            'hostname' => $hostname,
            'username' => $username,
            'password' => $password,
            'database' => $database,
        ]);

        $this->sql = new Sql($this->adapter);
    }
}
