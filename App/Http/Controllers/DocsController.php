<?php

namespace App\Http\Controllers;

use Hub\Base\Controller;
use Hub\Base\View;

class DocsController extends Controller
{
    public function index()
    {
        return View::render('docs/index');
    }
}
