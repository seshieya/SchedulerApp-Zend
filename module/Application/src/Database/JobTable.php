<?php

namespace Application\Database;

class JobTable extends BaseTable
{
    public function insertJobData(array $data) {
        $insert = $this->sql
            ->insert('job')
            ->values($data);

        $query = $this->sql->buildSqlString($insert);

        return $this->adapter->query($query)->execute();
    }
}