<?php

namespace app\model;

use app\config\DatabaseHandler;
use app\utils\PasswordEncoder;


class User extends BaseModel
{
    private $table_name = 'users_tb';

    function __construct(DatabaseHandler $databaseHandler)
    {
        parent::__construct($databaseHandler);
    }



    // status type ::: delete = 4,

    function createUser(array $payload)
    {
        if ($this->isUser($payload["_email"]) === true) {
            $payload["_password"] = PasswordEncoder::encodePassword($payload["_password"]);
            $sql = "INSERT INTO $this->table_name(user_slug, user_fullname, user_email, user_password) VALUES(:user_slug, :_fullname, :_email, :_password)";
            $response = $this->insert($sql, $payload, "user_slug");
            return $response;
        }
        return "exist";
    }

    function updateUserPassword(array $payload)
    {
        $payload["_password"] = PasswordEncoder::encodePassword($payload["_password"]);
        $sql = "UPDATE $this->table_name SET user_password = :_password, updated_at = :updatedAt WHERE user_slug = :_slug";
        if ($this->update($sql, $payload)) {
            return true;
        }
        return false;
    }

    function updateUserPic(array $payload)
    {
        $sql = "UPDATE $this->table_name SET user_picture = :_picture, updated_at = :updatedAt WHERE user_id = :_id";
        if ($this->update($sql, $payload)) {
            return true;
        }
        return false;
    }

    function fetchUsers()
    {
        $sql = "SELECT * FROM $this->table_name";
        $response = $this->fetchMany($sql);
        return $response;
    }

    function fetchUserByUsername(string $email)
    {
        $sql = "SELECT * from $this->table_name WHERE user_email = ?";
        $response = $this->fetch($sql, [$email]);
        return $response;
    }


    function isUser(string $email): bool
    {
        $sql = "SELECT user_slug from $this->table_name WHERE user_email = ?";
        $stmt = $this->query($sql, [$email]);
        return $stmt->rowCount() == 0;
    }
}
