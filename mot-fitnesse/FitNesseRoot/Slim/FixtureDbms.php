<?php

class FixtureDbms
{
    protected $username;
    protected $password;
    protected $hostname;
    protected $lastCmd;


    public function __construct($username, $password, $hostname, $database) {
        $this->username = $username;
        $this->password = $password;
        $this->hostname = $hostname;
        $this->database = $database;
    }


    public function emptyTables($tables)
    {
        $parts = ['set foreign_key_checks=0'];
        foreach(explode(',', $tables) as $table) {
            $table = trim($table);
            $parts[] = "truncate table $table";
        }
        $parts[] = 'set foreign_key_checks=1';

        $this->lastCmd = sprintf('mysql -e "%s" %s -u%s -h%s -p%s',
                implode(';', $parts),
                $this->database,
                $this->username,
                $this->hostname,
                $this->password);

        exec($this->lastCmd);
    }
    public function command() {
        return $this->lastCmd;
    }
}
