<?php

namespace Application\Database;

class ScheduleTable extends BaseTable
{
    public function insertScheduleData(array $data) {
        $insert = $this->sql
            ->insert('schedule')
            ->values($data);

        $query = $this->sql->buildSqlString($insert);

        return $this->adapter->query($query)->execute();
    }

    //need a get version number option.............

    public function getVersionNumber($scheduleId) {
        $select = $this->sql
            ->select()
            ->columns(['version_num'])
            ->from('schedule')
            ->where('version_num = ' . $scheduleId);

        $query = $this->sql->buildSqlString($select);

        $row = $this->adapter->query($query)->execute();

        return $row['version_num'];
    }

    //code below get first row in table, not the last row
    /*public function getLastScheduleId() {
        $select = $this->sql
            ->select()
            ->columns(['sched_id'])
            ->from('schedule');

        $query = $this->sql->buildSqlString($select);

        $row = $this->adapter->query($query)->execute()->current();

        return $row['sched_id'];
    }*/

    public function getLastScheduleId() {
        //below statement is: SELECT sched_id FROM schedule ORDER BY sched_id DESC LIMIT 1
        $select = $this->sql
            ->select()
            ->columns(['sched_id'])
            ->from('schedule')
            ->order('sched_id DESC')
            ->limit(1);

        $query = $this->sql->buildSqlString($select);

        $row = $this->adapter->query($query)->execute()->current();

        return $row['sched_id'];
    }




}