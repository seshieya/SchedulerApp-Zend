<?php

namespace Application\Database;

class ScheduleRowTable extends BaseTable
{
    public function insertScheduleRowData(array $data) {
        $insert = $this->sql
            ->insert('schedule_row')
            ->values($data);

        $query = $this->sql->buildSqlString($insert);

        return $this->adapter->query($query)->execute();
    }
}