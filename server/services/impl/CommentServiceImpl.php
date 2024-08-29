<?php

namespace app\services\impl;

interface CommentServiceImpl
{
    function makeComment(array $data, $file): string;
    function getThreatComments(int $_id): string;
    function getMyCommentCount(int $_id): int;
    function removeComment(int $id): string;
}
