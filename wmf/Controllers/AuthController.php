<?php

namespace Controllers;


use Models\LoginForm;
use Models\User;
use Util\Auth;
use Util\BaseController;

/**
 * Class AuthController
 * @package Controllers
 */
class AuthController extends BaseController {

    /** @var $auth Auth|null*/
    private $auth;

    public function init() {
        $this->auth = \App::obj()->auth;
    }

    public function index() {
        $loggedIn = $this->auth->isLoggedIn();
        $this->redirect($loggedIn ? '/profile' : '/login');
    }

    public function login() {
        $form = null;
        if ($_POST && isset($_POST['email']) && isset($_POST['password'])) {
            $form = new LoginForm($_POST);
            if ($form->validate()) {
                $user = User::findByEmail($form->email);
                $this->auth->login($user->user_id);
                $this->redirect('/profile');
            }
        }
        return $this->render('login', ['model' => $form]);
    }

    public function logout() {
        $this->auth->logout();
        $this->redirect('/login');
    }

}