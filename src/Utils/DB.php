<?php

declare(strict_types=1);

namespace App\Utils;

use PDO;
use PDOException;

class DB
{
    private PDO $pdo;

    /**
     * Constructor for the DB Model.
     * Initializes a new PDO connection using the provided credentials.
     * Previously an instantiate method was used, which is not the correct way to instantiate a Model
     *
     * @param string $dsn The Data Source Name, or DSN, containing the information required to connect to the database.
     * @param string $user The username for the DSN string.
     * @param string $password The password for the DSN string.
     */
    public function __construct(string $dsn, string $user, string $password)
    {
        try {
            $this->pdo = new PDO($dsn, $user, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Error handling strategy
            ]);
        } catch (PDOException $e) {
            die('Connection failed: ' . $e->getMessage());
        }
    }

    public function beginTransaction(): void
    {
        $this->pdo->beginTransaction();
    }

    public function commit(): void
    {
        $this->pdo->commit();
    }

    public function rollBack(): void
    {
        $this->pdo->rollBack();
    }

    public function select($sql, $params = []): array
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }


    public function executeUpdate($sql, $params = []): int|false
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->rowCount();
    }


    public function lastInsertId(): string|false
    {
        return $this->pdo->lastInsertId();
    }
}
