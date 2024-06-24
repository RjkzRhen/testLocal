<?php

class Config
{
    private string $servername;
    private string $username;
    private string $password;
    private string $dbname;

    public function __construct($filename)
    {
        $iniFile = parse_ini_file($filename, true);
        $this->servername = $iniFile['servername'];
        $this->username = $iniFile['username'];
        $this->password = $iniFile['password'];
        $this->dbname = $iniFile['dbname'];
    }

    public function getServername(): string
    {
        return $this->servername;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getDbname(): string
    {
        return $this->dbname;
    }
}
