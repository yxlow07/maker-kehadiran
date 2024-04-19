<?php

namespace app\Controllers;

use app\Models\LoginModel;
use app\Models\RegisterModel;
use app\Models\UserModel;
use core\App;
use core\Controller;
use core\Cookies;
use core\View;

class AuthController extends Controller
{

    public function register(): void
    {
        $data = App::$app->request->data();
        $model = new RegisterModel($data);

        if (App::$app->request->isMethod('post')) {
            if ($model->validate() && $model->verifyNoDuplicate() && $model->registerUser()) {
                App::$app->session->setFlashMessage('success', 'Berjaya daftar rekod murid! Log masuk sekarang!');
                redirect('/login');
            }
        }

        echo View::make()->renderView('register', ['model' => $model]);
    }

    public function login(): void
    {
        $data = App::$app->request->data();
        $model = new LoginModel($data);

        if (App::$app->request->isMethod('post')) {
            if ($model->validate() && $model->verifyUser()) {
                App::$app->session->set('user', App::$app->user);
                App::$app->session->setFlashMessage('success', 'Berjaya log masuk');

                if ($model->rememberMe && !App::$app->user->isAdmin) {
                    App::$app->user->setCookies();
                }
                redirect();
            }
        }

        echo View::make()->renderView('login', ['model' => $model]);
    }

    public function logout(): void
    {
        App::$app->session->setFlashMessage('success', 'Berjaya Log Keluar');
        App::$app->session->delete('user');

        $sessionID = Cookies::getCookie('sessionID');
        if (!is_null($sessionID) && $sessionID == App::$app->user->infoMurid['sessionID']) {
            unset(App::$app->user->infoMurid['sessionID']);
            App::$app->database->update('murid', ['infoLogMasuk'], ['infoLogMasuk' => json_encode(App::$app->user->infoMurid)], ['idMurid' => App::$app->user->idMurid]);
        }

        Cookies::unsetCookies(['idMurid', 'sessionID']);
        redirect();
    }

}