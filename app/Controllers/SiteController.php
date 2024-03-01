<?php

namespace app\Controllers;

use core\App;
use core\Controller;
use core\View;

class SiteController extends Controller
{
    public function userNavItems(): array
    {
        return [
            '/' => 'Home',
            '/profile' => 'Profile',
            '/check_attendance' => 'Check Attendance',
            '/logout' => 'Logout',
        ];
    }

    public function render(): void
    {
        echo View::make()->renderView('index', ['nav' => $this->userNavItems()]);
    }
}