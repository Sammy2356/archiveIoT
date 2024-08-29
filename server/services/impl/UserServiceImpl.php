<?php

namespace app\services\impl;

interface UserServiceImpl
{
    function setUser(array $payload): string;
    function userAuthentication(string $username, string $password): string;
    function editUserPassword(array $payload): string;
    function editProfilePicture($file, int $_id): string;
}
