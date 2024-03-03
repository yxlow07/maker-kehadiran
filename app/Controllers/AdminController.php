<?php

namespace app\Controllers;

use app\Exceptions\MethodNotAllowedException;
use app\Exceptions\UserNotFoundException;
use app\Models\LoginModel;
use app\Models\ProfileModel;
use app\Models\RegisterModel;
use app\Models\UserModel;
use core\App;
use core\Controller;
use core\Exceptions\ViewNotFoundException;
use core\Models\BaseModel;
use core\Models\ValidationModel;
use core\View;
use http\Client\Curl\User;

class AdminController extends Controller
{
    public function createUsersRules():array
    {
        return [
            'idMurid' => [ValidationModel::RULE_REQUIRED, [ValidationModel::RULE_MIN, 'min' => 5]],
            'noTel' => [ValidationModel::RULE_REQUIRED, [ValidationModel::RULE_MIN, 'min' => 10], [ValidationModel::RULE_MAX, 'max' => 11]],
            'namaM' => [ValidationModel::RULE_REQUIRED],
            'kLMurid' => [ValidationModel::RULE_REQUIRED],
        ];
    }

    public function list_users(): void
    {
        $users = (array) App::$app->database->findAll('murid');
        $kehadirans = App::$app->database->findAll('kehadiran');
        $data = ['xaxis' => '[', 'yaxis' => '['];

        foreach ($kehadirans as $kehadiran) {
            $data['xaxis'] .= '"' . $kehadiran['idMurid'] . '",';
            $data['yaxis'] .= count(array_filter(json_decode($kehadiran['kehadiran']))) . ',';
        }

        $data['xaxis'] = trim($data['xaxis'], ',');
        $data['yaxis'] = trim($data['yaxis'], ',');
        $data['xaxis'] .= ']';
        $data['yaxis'] .= ']';

        echo View::make(['/views/admin/'])->renderView('users', ['users' => $users, 'data' => $data]);
    }

    public function createUsers(): void
    {
        $model = new RegisterModel(App::$app->request->data());

        if (App::$app->request->isMethod('post')) {
            if ($model->validate($this->createUsersRules()) && $model->verifyNoDuplicate() && $model->registerUser() && $model->registerName()) {
                App::$app->session->setFlashMessage('success', 'Successfully registered user.');
                header('Location: /crud_users');
            }
        }

        echo View::make(['/views/admin'])->renderView('create_users', ['model' => $model]);
    }

    /**
     * @throws ViewNotFoundException
     * @throws UserNotFoundException|MethodNotAllowedException
     */
    public function crud_users($idMurid, $action): void
    {
        $data = (array) LoginModel::getUserFromDB($idMurid, true);

        match ($action) {
            BaseModel::READ => '',
            BaseModel::UPDATE => $this->editUser($data),
            BaseModel::DELETE => UserModel::deleteUserFromDB($idMurid),
            'analysis' => $data = (array) UserModel::getAttendanceFromDatabase($idMurid),
            default => $data = BaseModel::UNDEFINED,
        };

        if (isset($data[0]) && $data[0] === false) {
            throw new UserNotFoundException();
        }

        if ($data === BaseModel::UNDEFINED) {
            throw new MethodNotAllowedException();
        }

        if ($action == 'analysis') {
            $data['xaxis'] = json_encode(array_map(fn($key) => "Aktiviti #" . $key + 1, array_keys(json_decode($data['kehadiran']))));
        }

        echo View::make(['/views/admin'])->renderView('user_profile', ['data' => $data, 'action' => $action]);
    }

    private function editUser($data)
    {
        $model = new ProfileModel($data);

        if (App::$app->request->isMethod('post')) {
            $model = new ProfileModel(App::$app->request->data());

            if ($model->validate() && $model->verifyNoDuplicate($data) && $model->updateDatabase($data)) {
                App::$app->session->setFlashMessage('success', 'Profile Updated Successfully!');
            }
        }

        echo View::make()->renderView('profile', ['model' => $model, 'isAdmin' => true]);
        exit;
    }
}