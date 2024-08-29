<?php

namespace app\controller;

use app\services\EngageService;
use app\services\LikeService;
use app\services\ServiceService;

class EngageController
{

    private $likeService;

    function __construct()
    {
        $this->likeService = new EngageService();
    }

    function likeToggle(array $payload)
    {
        return $this->likeService->changeLikeStatus($payload);
    }

    function followToggle(array $payload)
    {
        return $this->likeService->changeFollowStatus($payload);
    }

    function getCount()
    {
        return $this->likeService->getFollowersCount();
    }

    function registerEngagement(array $payload)
    {
        return $this->likeService->setEngagement($payload);
    }

    function getUserEngagement(int $threat, int $user)
    {
        return $this->likeService->getUserEngagements($threat, $user);
    }

    function likeCount(int $threat_id)
    {
        return $this->likeService->getTotalLikeEngagement($threat_id);
    }

    function followerCount(int $threat_id)
    {
        return $this->likeService->getTotalFollowEngagement($threat_id);
    }


    function userFollowerCount(int $_id)
    {
        return $this->likeService->getTotalUserFollowEngagement($_id);
    }
}
