<?php

namespace app\model;

use app\config\DatabaseHandler;


class Threat extends BaseModel
{
    private $table_name = 'threats_tb';

    function __construct(DatabaseHandler $databaseHandler)
    {
        parent::__construct($databaseHandler);
    }

    function insertThreat(array $payload)
    {
        if ($this->isThreat($payload["_title"]) === true) {
            $sql = "INSERT INTO $this->table_name(threat_slug, threat_title, reporter_id, threat_desc, threat_category, date_discovered, affected_devices, severity_level, iocs, mitigation_steps, attachment) 
                    VALUES(:_slug, :_title, :_user_id, :_desc, :_category, :_discovered, :_affected_devices, :_severity_level, :_iocs, :_mitigation_steps, :_attachment)";
            return $this->insert($sql, $payload, "_slug");
        }
        return "exist";
    }

    function updateThreat(array $payload)
    {
        if (count($payload) == 10) {
            if ($this->isSlug($payload["_slug"]) === true) {
                $sql = "UPDATE $this->table_name SET threat_title = :_title, threat_desc = :_desc, threat_category = :_category, date_discovered = :_discovered, affected_devices = :_affected_devices, severity_level = :_severity_level, iocs = :_iocs, mitigation_steps = :_mitigation_steps, attachment = :_attachment, updated_at = :updatedAt WHERE threat_slug = :_slug";
                return $this->update($sql, $payload);
            }
            return "not exist";
        }
        return false;
    }

    function fetchThreats()
    {
        $sql = "SELECT $this->table_name.*, users_tb.user_fullname, users_tb.user_email, users_tb.user_picture FROM $this->table_name LEFT JOIN users_tb ON $this->table_name.reporter_id = users_tb.user_id ORDER By created_at DESC";
        $response = $this->fetchMany($sql);
        return $response;
    }

    function fetchThreatsByReporter(int $_id)
    {
        $sql = "SELECT $this->table_name.*, users_tb.user_fullname, users_tb.user_email, users_tb.user_picture FROM $this->table_name LEFT JOIN users_tb ON $this->table_name.reporter_id = users_tb.user_id WHERE reporter_id = ?  ORDER By created_at DESC";
        $response = $this->fetchMany($sql, [$_id]);
        return $response;
    }

    function fetchThreatBySlug(string $slug)
    {
        $sql = "SELECT $this->table_name.*, users_tb.user_fullname, users_tb.user_email, users_tb.user_picture FROM $this->table_name LEFT JOIN users_tb ON $this->table_name.reporter_id = users_tb.user_id WHERE threat_slug = ? ";
        $response = $this->fetch($sql, [$slug]);
        return $response;
    }

    function removeThreat(string $slug)
    {
        $sql = "DELETE FROM $this->table_name WHERE threat_slug = ? ";
        $response = $this->delete($sql, [$slug]);
        return $response;
    }



    function updateLike(int $new_like, int $threat_id)
    {
        $sql = "UPDATE $this->table_name SET likes = :likes, updated_at = :updatedAt WHERE  threat_id = :threat_id";
        return $this->update($sql, ["likes" => $new_like, "threat_id" => $threat_id]);
    }


    function isThreat(string $threat_title): bool
    {
        $sql = "SELECT threat_slug from $this->table_name WHERE threat_title = ?";
        $stmt = $this->query($sql, [$threat_title]);
        return $stmt->rowCount() == 0;
    }

    function isSlug(string $slug): bool
    {
        $sql = "SELECT threat_id from $this->table_name WHERE threat_slug = ?";
        $stmt = $this->query($sql, [$slug]);
        return $stmt->rowCount() == 1;
    }
}
