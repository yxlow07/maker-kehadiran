<?php

namespace app\Controllers;

use app\Exceptions\MethodNotAllowedException;
use app\Exceptions\UserNotFoundException;
use app\Models\AdminModel;
use app\Models\LoginModel;
use app\Models\ProfileModel;
use app\Models\RegisterModel;
use app\Models\UserModel;
use core\App;
use core\Controller;
use core\Database\CSVDatabase;
use core\Database\Generator;
use core\Exceptions\ViewNotFoundException;
use core\Filesystem;
use core\Models\BaseModel;
use core\Models\ValidationModel;
use core\Router\Request;
use core\View;

class AdminController extends Controller
{
    public function render(string $view, array $params = []): void
    {
        echo View::make(['/views/admin/'])->renderView($view, $params);
    }

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

        $this->render('users', ['users' => $users]);
    }

    public function createUsers(): void
    {
        $model = new RegisterModel(App::$app->request->data());

        if (App::$app->request->isMethod('post')) {
            if ($model->validate($this->createUsersRules()) && $model->verifyNoDuplicate() && $model->registerUser() && $model->registerName()) {
                App::$app->session->setFlashMessage('success', 'Rekod Murid Dicipta Berjaya!');
                redirect('/crud_users');
            }
        }

        $this->render('create_users', ['model' => $model]);
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

        $this->render('user_profile', ['data' => $data, 'action' => $action]);
    }

    private function editUser($data)
    {
        $model = new ProfileModel($data);

        if (App::$app->request->isMethod('post')) {
            $model = new ProfileModel(App::$app->request->data());

            if ($model->validate() && $model->verifyNoDuplicate($data) && $model->updateDatabase($data)) {
                App::$app->session->setFlashMessage('success', 'Berjaya kemaskini!');
            }
        }

        $this->render('profile', ['model' => $model, 'isAdmin' => true]);
        exit;
    }

    public function crud_announcements()
    {
        $data = CSVDatabase::returnAllData(Filesystem::resources('/data/announcements.csv'));

        if (App::$app->request->isMethod('post')) {
            $this->uploadAnnouncements();
        }

        $this->render('crud_announcements', ['data' => $data]);
    }

    private function uploadAnnouncements()
    {
        header('Content-Type: application/json; charset=utf-8');
        $data = json_decode(App::$app->request->data(true), true);
        $cleanedData = [];

        foreach ($data['data'] as $datum) {
            $cleanedData[$datum[0]] = htmlspecialchars($datum[1], ENT_NOQUOTES, "UTF-8");
        }

        $gen = new Generator();
        $gen->generateCSVFile($cleanedData, Filesystem::resources('/data/announcements.csv'));

        echo json_encode(['success' => true]);
        exit();
    }

    public function kehadiran()
    {
        $idMurid = App::$app->user->idMurid;
        $exists = LoginModel::getUserFromDB($idMurid);

        if (!$exists) {
            throw new UserNotFoundException();
        }

        $kehadiran = (array) UserModel::getAttendanceFromDatabase($idMurid);
        $kehadiran['kehadiran'] = json_decode($kehadiran['kehadiran']);

        if (App::$app->request->isMethod('post')) {
            header('Content-Type: application/json;charset=utf-8');
            $data = json_decode(App::$app->request->data(true), true)['data'];
            echo json_encode(['success' => UserModel::updateAttendanceRecord($idMurid, $data)]);
            exit;
        }

        $this->render('kehadiran', ['data' => $kehadiran]);
    }

    public function upload_kehadiran(): void
    {
        $uploadResults = [];
        if (App::$app->request->isMethod('post') && isset($_FILES['csv']) && $_FILES['csv']['error'] == UPLOAD_ERR_OK) {
            $csv = file($_FILES['csv']['tmp_name'], FILE_IGNORE_NEW_LINES);
            $result = [];

            foreach ($csv as $line) {
                $data = str_getcsv($line);
                $id = array_shift($data);
                $result[$id] = array_map(fn($m) => $m == 1, $data);
            }

            foreach ($result as $idMurid => $attendanceData) {
                $exists = LoginModel::getUserFromDB($idMurid);
                if (!$exists) {
                    $uploadResults[] = "$idMurid tidak ditemui dalam pangkalan data!";
                    continue;
                }
                $success = UserModel::updateAttendanceRecord($idMurid, $attendanceData) ? 'successfully' : 'unsuccessfully';
                $uploadResults[] = "Berjaya kemaskini untuk murid $idMurid!";
            }
            
            App::$app->session->setFlashMessage('success', 'Berjaya muat naik fail csv!');
        }
        $this->render('upload_attendance', ['results' => $uploadResults]);
    }

    public function analysis_kehadiran()
    {
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

        $this->render('attendance', ['data' => $data]);
    }

    public function add_admin()
    {
        $model = new AdminModel();
        if (App::$app->request->isMethod('post')) {
            $model = new AdminModel(App::$app->request->data());

            if ($model->validate($model->newAdminRules()) && $model->verifyNoDuplicate() && $model->updateDatabase()) {
                App::$app->session->setFlashMessage('success', 'Berjaya mencipta rekod admin');
                redirect();
            }
        }

        $this->render('add_admin', ['model' => $model, 'isAdmin' => true]);
    }

    public function find_student()
    {
        $data = [];

        if (App::$app->request->isMethod('post')) {
            $query = '%' . App::$app->request->data()['query'] . '%';
            $data = App::$app->database->findAll('murid', conditions: ['idMurid' => $query, 'noTel' => $query], isSearch: true);
            App::$app->request->setHeader('json');
            App::$app->response->sendJson($data, true);
        }

        $this->render('search', ['users' => $data]);
    }
}