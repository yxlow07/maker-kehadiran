<?php

namespace app\Controllers;

use core\Controller;
use core\View;

class SiteController extends Controller
{
    public function render(): void
    {
        echo View::make()->renderView('index');
    }
}