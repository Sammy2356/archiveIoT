<?php

namespace app\model;

use app\config\DatabaseHandler;


class Notification extends BaseModel
{
    private $table_name = 'notifications_tb';
    private $table_read = 'user_notification_tb';

    function __construct(DatabaseHandler $databaseHandler)
    {
        parent::__construct($databaseHandler);
    }



    // Insert Notification

    function insertNotification(array $payload)
    {
        $sql = "INSERT INTO $this->table_name(notification_slug, notification_desc, notification_threat_slug) 
                    VALUES(:_slug, :_description, :_threat_slug)";
        return $this->insert($sql, $payload, "_slug");
    }

    // Fetch User Followed Notification
    function fetchFollowedNotification(int $user_id)
    {
        $sql = "SELECT $this->table_name.* FROM $this->table_name LEFT JOIN threats_tb ON threats_tb.threat_slug = $this->table_name.notification_threat_slug LEFT JOIN engages_tb ON engages_tb.engage_threat_id = threats_tb.threat_id WHERE engages_tb.engage_user_id = ? AND engages_tb.follow_status = ? ORDER BY created_at DESC";
        return $this->fetchMany($sql, [$user_id, 1]);
    }



    // On Notification Viewed

    // Register User as read Or Mark User Notification as Read

    function insertReadNotification(array $payload)
    {
        $sql = "INSERT INTO $this->table_read(un_slug, un_notification_id, un_user_id) VALUES(:_slug, :_notification_id, :_user_id)";
        return $this->insert($sql, $payload, "_slug");
    }

    // Fetch User Read Notification - Notification Id
    function fetchUserReadNotification(int $user_id)
    {
        $sql = "SELECT un_notification_id FROM $this->table_read WHERE un_user_id = ?";
        return $this->fetchMany($sql, [$user_id]);
    }
}
