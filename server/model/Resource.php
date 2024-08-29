<?php

namespace app\model;

use app\config\DatabaseHandler;
use app\utils\PasswordEncoder;


class Resource extends BaseModel
{
    private $table_name = 'threat_file_tb';

    function __construct(DatabaseHandler $databaseHandler)
    {
        parent::__construct($databaseHandler);
    }

    function insertResources(array $payload)
    {
        if ($this->isResources($payload["_path"]) === true) {

            $sql = "INSERT INTO $this->table_name(file_slug, threat_id, filepath) VALUES(:_slug, :_user_id, :_threat_id, :_path)";
            $response = $this->insert($sql, $payload, "_slug");
            return $response;
        }
        return "exist";
    }

    function fetchResourcesByThreat(int $threat_id)
    {
        $sql = "SELECT * FROM $this->table_name WHERE threat_id = ? ";
        $response = $this->fetchMany($sql, [$threat_id]);
        return $response;
    }

    function isResources(string $_path): bool
    {
        $sql = "SELECT file_slug from $this->table_name WHERE filepath = ?";
        $stmt = $this->query($sql, [$_path]);
        return $stmt->rowCount() == 0;
    }
}
