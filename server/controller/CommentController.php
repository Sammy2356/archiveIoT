<?php

namespace app\controller;

use app\services\CommentService;

class CommentController
{

    private $commentService;

    function __construct()
    {
        $this->commentService = new CommentService();
    }

    function addComment(array $payload, $file)
    {
        return $this->commentService->makeComment($payload, $file);
    }

    function getMyCommentCount(int $_id)
    {
        return $this->commentService->getMyCommentCount($_id);
    }
    function getCount()
    {
        return $this->commentService->getCommentsCount();
    }
    function getThreatComments(int $_id)
    {
        return $this->commentService->getThreatComments($_id);
    }

    function deleteComment(int $id)
    {
        return $this->commentService->removeComment($id);
    }
}
