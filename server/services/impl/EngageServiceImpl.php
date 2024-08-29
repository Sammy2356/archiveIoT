<?php

namespace app\services\impl;

interface EngageServiceImpl
{
    function setEngagement(array $payload): bool;
    function changeLikeStatus(array $payload): bool;
    function changeFollowStatus(array $payload): bool;
    function getUserEngagements(int $threat_id, int $user_id): string;
    function getTotalFollowEngagement(int $threat_id);
    function getTotalUserFollowEngagement(int $user_id);
    function getTotalLikeEngagement(int $threat_id);
}
