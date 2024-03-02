<?php

namespace app\Controllers;

use core\App;
use core\Controller;
use core\View;

class AdminController extends Controller
{
    public function crud_users()
    {
        $users = (array) App::$app->database->findAll('murid');
        echo View::make(['/views/admin/'])->renderView('users', ['users' => $users]);
    }
}