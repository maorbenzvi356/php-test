<?php

class DB
{
	private $pdo;

	public function __construct($dsn, $user, $password)
	{
		$this->pdo = new \PDO($dsn, $user, $password);
	}

    public function select($sql, $params = [])
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }


    public function executeUpdate($sql, $params = [])
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->rowCount(); // Returns the number of affected rows
    }


	public function lastInsertId()
	{
		return $this->pdo->lastInsertId();
	}
}
