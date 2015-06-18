<?php

namespace Util;


class Auth {

    public function __construct()
    {
        session_start();
    }

    public function login($userId)
    {
        $_SESSION['user'] = $userId;
        session_write_close();
    }

    public function logout()
    {
        session_destroy();
    }

    public function isLoggedIn ()
    {
        return isset($_SESSION['user']) && $_SESSION['user'] !== null;
    }

    public function hashPassword($password)
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    public function checkPassword($password, $hash)
    {
        return password_verify($password, $hash);
    }
}