<?php

namespace app\services;

use app\config\MysqlDBH;
use app\dto\ResponseDto;
use app\model\User;
use app\services\impl\UserServiceImpl;
use app\utils\MediaFileHandler;
use app\utils\PasswordEncoder;

class UserService implements UserServiceImpl
{

    private $model;

    function __construct()
    {
        $mysqlConnector = new MysqlDBH();
        $this->model = new User($mysqlConnector);
    }

    function setUser(array $data): string
    {
        $response = $this->model->createUser($data);
        if (is_bool($response)) {
            if ($response) {
                return ResponseDto::json("New user account registration was successful!", 201);
            }
            return ResponseDto::json("An error was encountered while trying to register user details!", 500);
        }
        return ResponseDto::json("This user detail already exist in our system!", 422);
    }

    function getUsers(): string
    {
        $response = $this->model->fetchUsers();
        return ResponseDto::json($response);
    }


    function userAuthentication(string $username, string $password): string
    {
        $response = $this->model->fetchUserByUsername($username);
        if (count($response) > 4) {
            $passwordHash = $response["user_password"];
            if (PasswordEncoder::decodePassword($password, $passwordHash)) {
                // $response["user_password"] = "_____";
                return ResponseDto::json("Login was successful", 200, $response);
            }
            return ResponseDto::json("Your password is not recognized!");
        }
        return ResponseDto::json("Your username is not recognized!");
    }


    function comparePassword($hash, $password): bool
    {
        return PasswordEncoder::decodePassword($password, $hash);
    }


    function editUserPassword(array $payload): string
    {
        $response = $this->model->updateUserPassword($payload);
        if ($response) {
            return ResponseDto::json("User password was updated successfully", 200);
        }
        return ResponseDto::json("We are unable to update your password. Please try again!");
    }

    function editProfilePicture($file, int $_id): string
    {

        $media = new MediaFileHandler();

        if ($media->singleFileUpload($file) == true) {
            $payload = array(
                '_picture' => $media->getMediaUrl(),
                '_id' => $_id
            );
            $response = $this->model->updateUserPic($payload);
            if ($response) return ResponseDto::json("User profile picture was updated successfully", 200, [$media->getMediaUrl()]);
        }
        return ResponseDto::json("We are unable to update your profile picture. Please try again!");
    }
}
