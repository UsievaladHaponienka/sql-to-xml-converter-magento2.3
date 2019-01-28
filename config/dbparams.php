<?php

class DbParams
{
    /**
     * @var string host
     */
    private $host = '';

    /**
     * @var string database_name
     */
    private $dbname = '';

    /**
     * @var string username
     */
    private $username = '';

    /**
     * @var string $password
     */
    private $password = '';

    /**
     * @return array
     */
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