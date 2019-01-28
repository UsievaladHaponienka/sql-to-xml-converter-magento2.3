<?php

include_once ROOT . '/config/dbparams.php';

class Connection
{
    private $paramsObject;

    public function __construct()
    {
        $this->paramsObject = new DbParams();
    }

    public function getConnection()
    {
        $params = $this->paramsObject->getDbParams();
        $db = new PDO("mysql:host={$params['host']};dbname={$params['dbname']}",
            "{$params['username']}", "{$params['password']}");
        return $db;
    }
}