<?php

namespace app\model;

use app\config\DatabaseHandler;


class Engage extends BaseModel
{
    private $table_name = 'engages_tb';

    function __construct(DatabaseHandler $databaseHandler)
    {
        parent::__construct($databaseHandler);
    }


    // register engagement
    function registerEngagement($payload)
    {
        if ($this->isRegistered($payload["_user_id"], $payload["_threat_id"])) {
            $sql = "INSERT INTO $this->table_name(engage_slug, engage_threat_id, engage_user_id, like_status, follow_status) 
                    VALUES(:_slug, :_threat_id, :_user_id, :_like, :_follow)";
            return $this->insert($sql, $payload, "_slug");
        }
        return false;
    }
    // toggleLike
    function toggleLikeStatus(array $payload)
    {
        $sql = "UPDATE $this->table_name SET like_status = :_like, updated_at = :updatedAt WHERE engage_slug = :_slug";
        return $this->update($sql, $payload);
    }
    // toggleFollower
    function toggleFollowStatus(array $payload)
    {
        $sql = "UPDATE $this->table_name SET follow_status = :_follow, updated_at = :updatedAt WHERE engage_slug = :_slug";
        return $this->update($sql, $payload);
    }
    // fetchThreatLikeEngagement
    function fetchThreatLikes(int $threat_id)
    {
        $sql = "SELECT engage_slug FROM $this->table_name WHERE engage_threat_id = ? AND like_status = ?";
        return $this->count($sql, [$threat_id, 1]);
    }

    // fetchThreatFollowEngagement
    function fetchThreatFollow(int $threat_id)
    {
        $sql = "SELECT engage_slug FROM $this->table_name WHERE engage_threat_id = ? AND follow_status = ?";
        return $this->count($sql, [$threat_id, 1]);
    }

    function fetchFollowers()
    {
        $sql = "SELECT engage_slug FROM $this->table_name WHERE follow_status = ?";
        return $this->count($sql, [1]);
    }

    function fetchUserFollower(int $user_id)
    {
        $sql = "SELECT engage_slug FROM $this->table_name WHERE engage_user_id = ? AND follow_status = ?";
        return $this->count($sql, [$user_id, 1]);
    }

    // fetchUserEngagement
    function fetchUserThreatEngagement(int $threat_id, int $user_id)
    {
        $sql = "SELECT engage_slug, follow_status, like_status FROM $this->table_name WHERE engage_threat_id = ? AND engage_user_id=?";
        return $this->fetch($sql, [$threat_id, $user_id]);
    }

    function isRegistered(int $user_id, int $threat_id): bool
    {
        $sql = "SELECT engage_slug from $this->table_name WHERE engage_threat_id = ? AND engage_user_id = ?";
        $stmt = $this->query($sql, [$threat_id, $user_id]);
        return $stmt->rowCount() == 0;
    }
}
