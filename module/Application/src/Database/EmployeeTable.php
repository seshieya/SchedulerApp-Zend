<?php

namespace Application\Database;

class EmployeeTable extends BaseTable
{
    public function insertScheduleData(array $data) {
        $insert = $this->sql
            ->insert('employee')
            ->values($data);

        $query = $this->sql->buildSqlString($insert);

        return $this->adapter->query($query)->execute();
    }
}