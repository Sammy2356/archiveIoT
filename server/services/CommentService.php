<?php

namespace app\services;

use app\config\MysqlDBH;
use app\dto\ResponseDto;
use app\model\Comment;
use app\model\Service;
use app\model\Threat;
use app\services\impl\CommentServiceImpl;
use app\services\impl\ThreatServiceImpl;
use app\utils\MediaFileHandler;

class CommentService implements CommentServiceImpl
{

    private $model;

    function __construct()
    {
        $connector = new MysqlDBH();
        $this->model = new Comment($connector);
    }

    function makeComment(array $payload, $file): string
    {
        $media = new MediaFileHandler();

        if ($media->singleFileUpload($file) == true) {
            $payload['_attachment'] = $media->getMediaUrl();
            $response = $this->model->insertComment($payload);
            if (is_bool($response)) return ResponseDto::json("Comment was created successfully!", 201);

            if (is_string($response) && $response == "exist")
                return ResponseDto::json("You have made this comment just now!", 422);

            return ResponseDto::json("An error was encountered while trying to create comment!", 500);
        }
        return ResponseDto::json("An issue was encountered while trying to upload the media!", 422);
    }

    function getThreatComments(int $_id): string
    {
        $response = $this->model->fetchCommentByThreat($_id);
        return ResponseDto::json($response);
    }

    function getMyCommentCount(int $_id): int
    {
        return $this->model->fetchMyCommentCount($_id);
    }

    function getThreatsCount(int $id): int
    {
        $response = $this->model->fetchMyCommentCount($id);
        return $response;
    }


    function getCommentsCount(): int
    {
        return $this->model->fetchCommentCount();
    }


    function removeComment(int $id): string
    {
        $response = $this->model->removeComment($id);
        if ($response) {
            return ResponseDto::json("Comment was deleted successfully", 200);
        }
        return ResponseDto::json("We are unable to delete this comment at the moment. Please try again!");
    }
}
