<?php

namespace app\Controllers;

use app\Models\LoginModel;
use app\Models\ProfileModel;
use app\Models\User;
use core\App;
use core\Controller;
use core\View;

class UserController extends Controller
{

    public function profile()
    {
        $model = new ProfileModel(App::$app->request->data());

        if (App::$app->request->isMethod('post')) {
            if ($model->validate() && $model->verifyNoDuplicate() && $model->updateDatabase()) {
                App::$app->session->setFlashMessage('success', 'Profile Updated Successfully!');
                App::$app->user = LoginModel::getUserFromDB($model->idMurid);
                App::$app->session->set('user', App::$app->user);
            }
        }

        echo View::make()->renderView('profile', ['model' => $model]);
    }
}