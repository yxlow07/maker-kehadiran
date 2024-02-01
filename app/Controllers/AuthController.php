<?php

namespace app\Controllers;

use app\Models\LoginModel;
use app\Models\User;
use core\App;
use core\Controller;
use core\Cookies;
use core\View;

class AuthController extends Controller
{
    public function render(): void
    {
        echo "";
    }

    public function renderRegisterPage(): void
    {
        echo View::make()->renderView('register');
    }

    public function renderLoginPage(?LoginModel $loginModel = null): void
    {
        echo View::make()->renderView('login', ['model' => $loginModel]);
    }

    public function login(): void
    {
        $data = App::$app->request->data();
        $model = new LoginModel($data);

        if ($model->validate() && $model->verifyUser()) {
            App::$app->session->set('user', App::$app->user);
            App::$app->session->setFlashMessage('loginSuccess', 'Login successfully');

            if ($model->rememberMe) {
                App::$app->user->setCookies();
            }
             header("Location: /");
        }

        $this->renderLoginPage($model);
    }

    public function logout()
    {
        App::$app->session->setFlashMessage('loginSuccess', 'Logout successful');
        App::$app->session->delete('user');
        Cookies::unsetCookies(['idMurid', 'sessionID']);
        header('Location: /');
    }

}