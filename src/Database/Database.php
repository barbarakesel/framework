<?php

namespace Varvara\Framework\Database;

use PDO;
use PDOException;
use Exception;

class Database
{
    private PDO $connection;

    public function __construct()
    {
        $this->connect();
    }

    private function connect(): void
    {
        try {
            $host = $_ENV['DB_HOST'];
            $port = $_ENV['DB_PORT'];
            $dbname = $_ENV['DB_NAME'];
            $user = $_ENV['DB_USER'];
            $password = $_ENV['DB_PASSWORD'];

            $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";

            $this->connection = new PDO($dsn, $user, $password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            throw new Exception('Database connection error: ' . $e->getMessage());
        }
    }

    public function execute(string $query, array $params = []): bool
    {
        $stmt = $this->connection->prepare($query);
        return $stmt->execute($params);
    }

    public function fetchAll(string $query, array $params = []): array
    {
        $stmt = $this->connection->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function fetchModel(string $query, string $modelClass, array $params = [])
    {
        $stmt = $this->connection->prepare($query);
        $stmt->execute($params);
        $stmt->setFetchMode(PDO::FETCH_CLASS, $modelClass);
        return $stmt->fetch();
    }

    public function fetchAllModels(string $query, string $modelClass, array $params = []): array
    {
        $stmt = $this->connection->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_CLASS, $modelClass);
    }


    public function getConnection(): PDO
    {
        return $this->connection;
    }
}
