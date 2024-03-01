<?php

namespace app\Controllers;

use app\Models\LoginModel;
use app\Models\ProfileModel;
use app\Models\UserModel;
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

    public function check_attendance()
    {
        $attendance_record = (array) UserModel::getAttendanceFromDatabase(App::$app->user->idMurid);
        $attendance_record['kehadiran'] = json_decode($attendance_record['kehadiran']);

        echo View::make()->renderView('check_attendance', ['record' => $attendance_record]);
    }
}