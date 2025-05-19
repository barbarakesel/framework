<?php

namespace Varvara\Framework\App;

use Exception;
use PDO;
use PDOException;

class DatabaseConnection
{
    private PDO $connection;

    public function __construct()
    {
        $this->connect();
    }

    private function connect(): void
    {
        try {
            $dsn = 'pgsql:host=db;dbname=framework';
            $username = 'postgres';
            $password = '2328';

            $this->connection = new PDO($dsn, $username, $password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            throw new Exception('Database connection error: ' . $e->getMessage());
        }
    }

    public function getConnection(): PDO
    {
        return $this->connection;
    }
}
