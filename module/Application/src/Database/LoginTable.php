<?php

namespace Application\Database;

class LoginTable extends BaseTable
{
    public function insertLoginData(array $data) {
        $insert = $this->sql
            ->insert('Login')
            ->values($data);

        $query = $this->sql->buildSqlString($insert);

        return $this->adapter->query($query)->execute();
    }

    public function getPassword($username) {
        $select = $this->sql
            ->select()
            ->columns(['password'])
            ->from('login')
            ->where('username = "' . $username . '"');

        $query = $this->sql->buildSqlString($select);

        $row = $this->adapter->query($query)->execute()->current();

        return $row['password'];
    }
}