<?php

namespace app\controller;

use app\services\UserService;

class UserController
{

    private $userService;

    function __construct()
    {
        $this->userService = new UserService();
    }
    function createNewAccount(array $payload)
    {
        return $this->userService->setUser($payload);
    }

    // function getAdmins()
    // {
    //     return $this->userService->getUsers();
    // }

    function userLoginAuthentication(string $email, string $password)
    {
        return $this->userService->userAuthentication($email, $password);
    }

    function modifyUserPassword(array $payload)
    {
        return $this->userService->editUserPassword($payload);
    }


    function modifyUserPicture($file, int $_id)
    {
        return $this->userService->editProfilePicture($file, $_id);
    }

    function getPasswordComparism(string $hash, string $password)
    {
        return $this->userService->comparePassword($hash, $password);
    }
}
