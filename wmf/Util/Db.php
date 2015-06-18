<?php

namespace Util;


use PDO;
use PDOException;

class Db {

    /**
     * @var $db PDO
     */
    private $db = null;

    public function __construct()
    {
        $this->connect();
    }

    private function connect()
    {
        $conf = \App::obj()->getConfig('db');
        $connectionStr = sprintf('mysql:host=%s;dbname=%s', $conf['host'], $conf['database']);
        try {
            $this->db = new PDO($connectionStr, $conf['username'], $conf['password']);
        } catch (PDOException $e) {
            echo "Error!: " . $e->getMessage();
            die();
        }
    }

    public function query($sql, $parameters)
    {
        $st = $this->db->prepare($sql);
        $st->execute($parameters);
        return $st->fetchAll();
    }

    public function insert($table, $data)
    {
        $fields = $placeholders = $values = [];
        foreach ($data as $key => $val) {
            $fields[] = "`$key`";
            $placeholders[] = ":$key";
            $values[":$key"] = $val;
        }
        $sql = sprintf("INSERT INTO %s (%s) VALUES (%s)", $table, implode(', ', $fields), implode(', ', $placeholders));
        $st = $this->db->prepare($sql);
        $st->execute($values);
        return $this->db->lastInsertId();
    }

}