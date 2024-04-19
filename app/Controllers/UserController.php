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
                '/check_attendance' => ['fa-clipboard-user', 'Semak Kehadiran'],
                '/edit_attendance' => [['fa-clipboard-user', 'fa-pencil-alt'], 'Kemaskini Kehadiran', true],
                '/profile' => ['fa-user', 'Profil Murid'],
            ],
            'admin' => [
                '/add_admin' => [['fa-user-tie', 'fa-plus'], 'Tambah Admin', true],
                '/crud_users' => [['fa-users', 'fa-pencil-alt'], 'Edit Murid', true],
                '/find_user' => [['fa-users', 'fa-magnifying-glass'], 'Cari Rekod Murid', true],
                '/analysis_attendance' => [['fa-users', 'fa-chart-pie-simple'], 'Analisis Kehadiran', true],
                '/crud_announcements' => [['fa-megaphone', 'fa-pencil-alt'], 'Edit Pengumuman', true],
            ],
            'general' => [
                '/' => ['fa-house', 'Laman Utama'],
                '/announcements' => ['fa-megaphone', 'Pengumuman'],
            ],
            'end' => [
                '/logout' => ['fa-person-from-portal', 'Log Keluar'],
            ],
            'guest' => [
                '/login' => ['fa-person-to-door', 'Log Masuk'],
                '/register' => ['fa-user-plus', 'Daftar Akaun'],
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
            App::$app->session->setFlashMessage('success', 'Berjaya kemaskini!');
            LoginModel::setNewUpdatedUserData($model->idMurid);
        }
    }

    private function handleUpdatePassword(ProfileModel $model): void
    {
        if ($model->validate($model->rulesUpdatePassword()) && $model->checkPassword() && $model->updateDatabasePasswordOnly()) {
            App::$app->session->setFlashMessage('success', 'Berjaya kemaskini!');
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