<?php

namespace App\Controllers;

use App\Core\Helpers;

class HomeController extends Controller
{
    /**
     * @return void
     */
    public function indexAction()
    {
        Helpers::redirectTo('task/table');
    }
}
