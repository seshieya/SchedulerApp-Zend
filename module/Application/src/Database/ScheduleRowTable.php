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

    public function getScheduleRows($schedId) {
        $select = $this->sql
            ->select()
            ->from('schedule_row')
            ->where('sched_id = ' . $schedId);

        $query = $this->sql->buildSqlString($select);

        return $this->adapter->query($query)->execute();
    }

    public function getTradeInfo($schedId) {
        $select = $this->sql
            ->select()
            ->columns(['trade_name', 'trade_email'])
            ->from('schedule_row')
            ->where('sched_id = ' . $schedId);

        $query = $this->sql->buildSqlString($select);

        return $this->adapter->query($query)->execute();
    }


}