<?php

require_once __DIR__ . "/../config/db.php";

class CV
{
    private $conn;

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
    }

    public function getAll()
    {
        $sql = "SELECT * FROM cv";
        return $this->conn->query($sql);
    }
}