<?php

namespace app\services;

use app\config\MysqlDBH;
use app\dto\ResponseDto;
use app\model\Engage;
use app\services\impl\EngageServiceImpl;

class EngageService implements EngageServiceImpl
{

    private $model;

    function __construct()
    {
        $connector = new MysqlDBH();
        $this->model = new Engage($connector);
    }

    function setEngagement(array $payload): bool
    {
        $response = $this->model->registerEngagement($payload);
        if (is_bool($response)) return $response;
        return false;
    }

    function changeLikeStatus(array $payload): bool
    {
        $response = $this->model->toggleLikeStatus($payload);
        if (is_bool($response)) return !$response;
        return false;
    }

    function changeFollowStatus(array $payload): bool
    {
        $response = $this->model->toggleFollowStatus($payload);
        if (is_bool($response)) return !$response;
        return false;
    }

    function getUserEngagements(int $threat_id, int $user_id): string
    {
        $response = $this->model->fetchUserThreatEngagement($threat_id, $user_id);
        return ResponseDto::json($response);
    }

    function getTotalLikeEngagement(int $threat_id)
    {
        return $this->model->fetchThreatLikes($threat_id);
    }

    function getTotalUserFollowEngagement(int $user_id)
    {
        return $this->model->fetchUserFollower($user_id);
    }


    function getFollowersCount(): int
    {
        return $this->model->fetchFollowers();
    }

    function getTotalFollowEngagement(int $threat_id)
    {
        return $this->model->fetchThreatFollow($threat_id);
    }
}
