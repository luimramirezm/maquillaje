<?php
class Db {
    private $host;
    private $user;
    private $pass;
    private $dbName;
    private $connection;

    public function __construct() {
        $this->host = getenv('DB_HOST') ?: 'localhost';
        $this->user = getenv('DB_USER') ?: 'root';
        $this->pass = getenv('DB_PASS') ?: '';
        $this->dbName = getenv('DB_NAME') ?: 'maquillaje_db';

        $this->connection = new mysqli($this->host, $this->user, $this->pass, $this->dbName);

        if ($this->connection->connect_error) {
            throw new Exception('Error de conexión: ' . $this->connection->connect_error);
        }
    }

    public function getConnection() {
        return $this->connection;
    }

}
