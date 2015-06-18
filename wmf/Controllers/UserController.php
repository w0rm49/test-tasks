<?php

namespace Controllers;


use Models\RegisterForm;
use Models\User;
use Util\BaseController;

class UserController extends BaseController {

    public function profile()
    {
        if(\App::obj()->auth->isLoggedIn()) {
            return $this->render('profile', ['user' => User::find($_SESSION['user'])]);
        }
        $this->redirect('/login');
    }

    public function register()
    {
        $model = null;

        if ($_POST) {
            $model = new RegisterForm(($_FILES) ? array_merge($_POST, $_FILES) : $_POST);
            if ($model->validate()) {
                $data = $model->export();
                $userId = User::create($data);
                if ($userId) {
                    \App::obj()->auth->login($userId);
                    $this->redirect('/profile');
                }
            }
        }

        return $this->render('register', ['model' => $model]);
    }

}