<?php

namespace app\Controllers;

use app\Models\LoginModel;
use app\Models\ProfileModel;
use app\Models\UserModel;
use core\App;
use core\Controller;
use core\Database\CSVDatabase;
use core\Models\ValidationModel;
use core\View;

class UserController extends Controller
{
    public function renderHome(): void
    {
        echo View::make()->renderView('index', ['nav' => $this->userNavItems()]);
    }

    public function userNavItems(): array
    {
        return [
            'user' => [
                '/profile' => 'Profile',
                '/check_attendance' => 'Check Attendance',
            ],
            'admin' => [
                '/crud_users' => 'Users List',
            ],
            'general' => [
                '/' => 'Home',
                '/announcements' => 'Announcements',
                '/logout' => 'Logout',
            ]
        ];
    }

    public function profilePage(): void
    {
        $model = new ProfileModel(App::$app->request->data());

        if (App::$app->request->isMethod('post')) {
            if ($model->type == ProfileModel::UPDATE_PROFILE) {
                $this->handleUpdateProfile($model);
            } else {
                $this->handleUpdatePassword($model);
            }
        }

        echo View::make()->renderView('profile', ['model' => $model]);
    }

    private function handleUpdateProfile(ProfileModel $model): void
    {
        if ($model->validate() && $model->verifyNoDuplicate() && $model->updateDatabase()) {
            App::$app->session->setFlashMessage('success', 'Profile Updated Successfully!');
            LoginModel::setNewUpdatedUserData($model->idMurid);
        }
    }

    private function handleUpdatePassword(ProfileModel $model): void
    {
        if ($model->validate($model->rulesUpdatePassword()) && $model->checkPassword() && $model->updateDatabasePasswordOnly()) {
            App::$app->session->setFlashMessage('success', 'Password Updated Successfully!');
            LoginModel::setNewUpdatedUserData(App::$app->user->idMurid);
        }
    }

    public function check_attendance(): void
    {
        $attendance_record = (array) UserModel::getAttendanceFromDatabase(App::$app->user->idMurid);
        $attendance_record['kehadiran'] = json_decode($attendance_record['kehadiran']);

        echo View::make()->renderView('check_attendance', ['record' => $attendance_record]);
    }

    public function announcements()
    {
        $announcements = CSVDatabase::returnAllData('announcements.csv');
        echo View::make()->renderView('announcements', ['announcements' => $announcements]);
    }
}