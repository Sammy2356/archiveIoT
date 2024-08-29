<?php

namespace app\services;

use app\config\MysqlDBH;
use app\dto\ResponseDto;
use app\model\Notification;
use app\model\Service;
use app\model\Threat;
use app\services\impl\ThreatServiceImpl;
use app\utils\MediaFileHandler;

class ThreatService implements ThreatServiceImpl
{

    private $model;
    private $notification_model;

    function __construct()
    {
        $connector = new MysqlDBH();
        $this->model = new Threat($connector);
        $this->notification_model = new Notification($connector);
    }

    function setThreat(array $payload, $file): string
    {
        $media = new MediaFileHandler();

        if ($media->singleFileUpload($file) == true) {
            $payload['_attachment'] = $media->getMediaUrl();
            $response = $this->model->insertThreat($payload);
            if (is_bool($response)) return ResponseDto::json("Threat post was created successfully!", 201);

            if (is_string($response) && $response == "exist")
                return ResponseDto::json("A report with this title already exist in our system!", 422);

            return ResponseDto::json("An error was encountered while trying to create service post!", 500);
        }
        return ResponseDto::json("An issue was encountered while trying to upload the media!", 422);
    }


    function editThreat(array $payload, $file): string
    {
        $media = new MediaFileHandler();

        if ($file !== null) {
            if ($media->singleFileUpload($file) == true) {
                $payload['_attachment'] = $media->getMediaUrl();
            } else {
                return ResponseDto::json("An issue was encountered while trying to upload the media!", 422);
            }
        }



        $response = $this->model->updateThreat($payload);
        if (is_bool($response) && $response) {
            // Register Notification
            $request = array(
                '_threat_slug' => $payload["_slug"],
                '_description' => "The threat report titled: " . $payload["_title"] . ", has been updated recently by the reporter."
            );
            $res = $this->notification_model->insertNotification($request);

            return ResponseDto::json("Threat report was updated successfully!", 200);
        }

        if (is_string($response) && $response == "not exist")
            return ResponseDto::json("Unknown report request. Please refresh your browser!", 422);

        return ResponseDto::json("An error was encountered while trying to update report!", 500);
    }

    function getThreats(): string
    {
        $response = $this->model->fetchThreats();
        return ResponseDto::json($response);
    }

    function getReporterThreat(int $_id): string
    {
        $response = $this->model->fetchThreatsByReporter($_id);
        return ResponseDto::json($response);
    }

    function getThreatBySlug(string $_slug): string
    {
        $response = $this->model->fetchThreatBySlug($_slug);
        return ResponseDto::json($response);
    }

    function getMyThreatsCount(int $_id): int
    {
        $response = $this->model->fetchThreatsByReporter($_id);
        return count($response);
    }

    function getThreatsCount(): int
    {
        $response = $this->model->fetchThreats();
        return count($response);
    }


    function deleteThreat(string $slug): string
    {
        $response = $this->model->removeThreat($slug);
        if ($response) {
            return ResponseDto::json("Threat report was deleted successfully", 200);
        }
        return ResponseDto::json("We are unable to delete this threat at the moment. Please try again!");
    }


    // Notifications

    function getUserFollowedNotifications(int $_id): string
    {
        $response = $this->notification_model->fetchFollowedNotification($_id);
        return ResponseDto::json($response);
    }


    function markNotification(array $payload): string
    {
        $response = $this->notification_model->insertReadNotification($payload);
        return ResponseDto::json($response);
    }

    function getUserReadNotifications(int $_id): string
    {
        $response = $this->notification_model->fetchUserReadNotification($_id);
        $ids = [];
        foreach ($response as $key => $id) {
            array_push($ids, $id["un_notification_id"]);
        }
        return ResponseDto::json($ids);
    }
}
