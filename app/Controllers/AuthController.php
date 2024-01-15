<?php

namespace app\Controllers;

use app\Models\LoginModel;
use core\App;
use core\Controller;
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
            echo "Logging in";
        }

        $this->renderLoginPage($model);
    }
}