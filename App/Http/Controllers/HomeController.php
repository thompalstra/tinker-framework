<?php
namespace App\Http\Controllers;

use Hub\Base\View;

class HomeController extends \Hub\Base\Controller
{
    public function index()
    {
        return View::render("index", ['title' => 'Home page']);
    }

    public function twig()
    {
        return View::render("template", ['title' => 'Twig page']);
    }

    public function smarty()
    {
        return View::render("smarty", ['title' => 'Smarty page']);
    }
}
