<?php

namespace Application\Database;

class TradeTable extends BaseTable
{
    public function insertTradeData(array $data) {
        $insert = $this->sql
            ->insert('trade')
            ->values($data);

        $query = $this->sql->buildSqlString($insert);

        return $this->adapter->query($query)->execute();
    }
}