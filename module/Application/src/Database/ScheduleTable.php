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


}