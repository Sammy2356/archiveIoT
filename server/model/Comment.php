<?php

namespace app\model;

use app\config\DatabaseHandler;


class Comment extends BaseModel
{
    private $table_name = 'comments_tb';

    function __construct(DatabaseHandler $databaseHandler)
    {
        parent::__construct($databaseHandler);
    }


    function insertComment(array $payload)
    {
        if ($this->isCommentExist($payload["_user_id"], $payload["_threat_id"], $payload["_description"]) === true) {

            $sql = "INSERT INTO $this->table_name(comment_slug, comment_user_id, comment_threat_id, comment_desc, comment_attachment) 
                    VALUES(:_slug, :_user_id, :_threat_id, :_description, :_attachment)";
            $response = $this->insert($sql, $payload, "_slug");
            return $response;
        }
        return "exist";
    }

    function fetchCommentByThreat(int $threat_id)
    {
        $sql = "SELECT $this->table_name.*, users_tb.user_fullname, users_tb.user_email, users_tb.user_picture FROM $this->table_name LEFT JOIN users_tb ON $this->table_name.comment_user_id = users_tb.user_id WHERE comment_threat_id = ? ";
        $response = $this->fetchMany($sql, [$threat_id]);
        return $response;
    }

    function fetchMyCommentCount(int $user_id)
    {
        $sql = "SELECT comment_slug FROM $this->table_name WHERE comment_user_id = ?";
        return $this->count($sql, [$user_id]);
    }

    function fetchCommentCount()
    {
        $sql = "SELECT * FROM $this->table_name";
        return $this->count($sql, []);
    }

    function removeComment(int $comment_id)
    {
        $sql = "DELETE FROM $this->table_name WHERE comment_id = ? ";
        $response = $this->delete($sql, [$comment_id]);
        return $response;
    }


    function isCommentExist(int $user_id, int $threat_id, string $desc): bool
    {
        $sql = "SELECT comment_slug from $this->table_name WHERE comment_threat_id = ? AND comment_user_id = ? AND comment_desc = ?";
        $stmt = $this->query($sql, [$threat_id, $user_id, $desc]);
        return $stmt->rowCount() == 0;
    }
}
