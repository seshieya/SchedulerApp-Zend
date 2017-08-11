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

    public function getLastEmployeeId() {
        $select = $this->sql
            ->select()
            ->columns(['emp_id'])
            ->from('employee');

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

        return $this->adapter->query($query)->execute();
    }
}