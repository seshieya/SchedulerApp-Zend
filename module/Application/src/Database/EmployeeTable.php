<?php

namespace Application\Database;

class EmployeeTable extends BaseTable
{
    public function insertEmployeeData(array $data) {
        $insert = $this->sql
            ->insert('employee')
            ->values($data);

        $query = $this->sql->buildSqlString($insert);

        return $this->adapter->query($query)->execute();
    }

    //code below get first row in table, not the last row
    /*public function getLastEmployeeId() {
        $select = $this->sql
            ->select()
            ->columns(['emp_id'])
            ->from('employee');

        $query = $this->sql->buildSqlString($select);

        $row = $this->adapter->query($query)->execute()->current();

        return $row['emp_id'];
    }*/

    public function getLastEmployeeId() {

        //below statement is: SELECT emp_id FROM employee ORDER BY emp_id DESC LIMIT 1
        $select = $this->sql
            ->select()
            ->columns(['emp_id'])
            ->from('employee')
            ->order('emp_id DESC')
            ->limit(1);

        $query = $this->sql->buildSqlString($select);

        $row = $this->adapter->query($query)->execute()->current();

        return $row['emp_id'];
    }

    public function getEmployeeDetails($empId) {
        $select = $this->sql
            ->select()
            ->from('employee')
            ->where('emp_id =' . $empId);

        $query = $this->sql->buildSqlString($select);

        return $this->adapter->query($query)->execute()->current();
    }
}