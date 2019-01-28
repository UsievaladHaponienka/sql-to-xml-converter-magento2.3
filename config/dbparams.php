<?php

class DbParams
{
    private $host = '';
    private $dbname = '';
    private $username = '';
    private $password = '';

    public function getDbParams()
    {
        return [
            'host'     => $this->host,
            'dbname'   => $this->dbname,
            'username' => $this->username,
            'password' => $this->password
        ];
    }
}