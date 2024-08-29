<?php

namespace app\model;

use app\config\DatabaseHandler;

class BaseModel
{


    private $dbconnector;

    function __construct(DatabaseHandler $databaseHandler)
    {
        $this->dbconnector = $databaseHandler->connection();
    }


    protected function insert(string $sql, array $payload, $slug_name): bool
    {
        $payload[$slug_name] = $this->generateSlug();
        $stmt = $this->dbconnector->prepare($sql);
        $stmt->execute($payload);
        if ($stmt->rowCount() == 1) {
            return true;
        }
        return false;
    }


    protected function insertOutputId(string $sql, array $payload, $slug_name): int
    {
        $payload[$slug_name] = $this->generateSlug();
        $stmt = $this->dbconnector->prepare($sql);
        if ($stmt->rowCount() == 1) {
            return $this->dbconnector->lastInsertId();
        }
        return 0;
    }

    protected function update($sql, $payload): bool
    {
        $payload["updatedAt"] = $this->getUpdatedDate();
        $stmt = $this->dbconnector->prepare($sql);
        $stmt->execute($payload);
        return ($stmt->rowCount() == 1);
    }

    protected function fetch($sql, $payload = []): array
    {
        $stmt = $this->dbconnector->prepare($sql);
        $stmt->setFetchMode(\PDO::FETCH_ASSOC);
        $stmt->execute($payload);
        $response = $stmt->fetch();
        return is_array($response) ? $response : [];
    }

    protected function fetchMany($sql, $payload = []): array
    {
        $stmt = $this->dbconnector->prepare($sql);
        $stmt->setFetchMode(\PDO::FETCH_ASSOC);
        $stmt->execute($payload);
        return $stmt->fetchAll();
    }

    protected function delete($sql, $payload)
    {
        $stmt = $this->dbconnector->prepare($sql);
        $stmt->execute($payload);
        return $stmt->rowCount() == 1;
    }

    protected function count($sql, $payload)
    {
        $stmt = $this->dbconnector->prepare($sql);
        $stmt->execute($payload);
        return $stmt->rowCount();
    }

    protected function query($sql, $payload)
    {
        $stmt = $this->dbconnector->prepare($sql);
        $stmt->execute($payload);
        return $stmt;
    }

    private function generateSlug(): string
    {
        return uniqid(time());
    }
    private function getUpdatedDate(): string
    {
        return date("Y-m-d h:i:s");
    }
}
