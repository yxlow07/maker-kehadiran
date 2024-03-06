<?php

namespace app\Controllers;

use app\Middleware\AuthMiddleware;
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
        echo View::make()->renderView('index', ['nav' => App::$app->nav]);
    }

    public static function navItems()
    {
        $navItems = [
            'user' => [
                '/check_attendance' => ['fa-clipboard-user', 'Check Attendance'],
                '/profile' => ['fa-user', 'Profile'],
            ],
            'admin' => [
                '/crud_users' => ['fa-users', 'Manage Users'],
                '/crud_announcements' => ['fa-scroll', 'Manage Announcements'],
            ],
            'general' => [
                '/' => ['fa-house', 'Home'],
                '/announcements' => ['fa-megaphone', 'Announcements'],
            ],
            'end' => [
                '/logout' => ['fa-person-from-portal', 'Logout'],
            ],
            'guest' => [
                '/login' => ['fa-person-to-door', 'Login'],
                '/register' => ['fa-user-plus', 'Register'],
            ],
        ];


        $nav = [
            ...$navItems['general'],
            ...(AuthMiddleware::execute() ? (App::$app->user instanceof UserModel ? $navItems['user'] + $navItems['end'] : $navItems['admin']+ $navItems['end']) : $navItems['guest']),
        ];

        App::$app->nav = $nav;
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
        $attendance_record['kehadiran'] = json_decode($attendance_record['kehadiran'] ?? []);

        echo View::make()->renderView('check_attendance', ['record' => $attendance_record]);
    }

    public function announcements()
    {
        $announcements = CSVDatabase::returnAllData('announcements.csv');
        echo View::make()->renderView('announcements', ['announcements' => $announcements]);
    }
}