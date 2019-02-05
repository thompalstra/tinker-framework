<?php

namespace App\Http\Controllers\Docs;

use Frame;

use Hub\Base\Controller;
use Hub\Base\View;

class HubController extends Controller
{


    public function index()
    {
        return View::render('docs/hub/index');
    }

    public function base()
    {
        return View::render('docs/hub/view', [
            'items' => $this->getItems(Frame::path(["Hub", "Base"]))
        ]);
    }

    public function cli()
    {
        return View::render('docs/hub/view', [
            'items' => $this->getItems(Frame::path(["Hub", "Cli"]))
        ]);
    }

    public function db()
    {
        return View::render('docs/hub/view', [
            'items' => $this->getItems(Frame::path(["Hub", "Db"]))
        ]);
    }

    public function http()
    {
        return View::render('docs/hub/view', [
            'items' => $this->getItems(Frame::path(["Hub", "Http"]))
        ]);
    }

    public function queue()
    {
        return View::render('docs/hub/view', [
            'items' => $this->getItems(Frame::path(["Hub", "Queue"]))
        ]);
    }

    public function smarty()
    {
        return View::render('docs/hub/view', [
            'items' => $this->getItems(Frame::path(["Hub", "Smarty"]))
        ]);
    }

    public function twig()
    {
        return View::render('docs/hub/view', [
            'items' => $this->getItems(Frame::path(["Hub", "Twig"]))
        ]);
    }

    public function getItems($path)
    {
        $items = [];
        foreach(scandir($path) as $file){
            if(in_array($file, ['.', '..'])){
                continue;
            }
            $filename = pathinfo($file)["filename"];
            $ns = Frame::ns([$path, $filename]);
            if(class_exists($ns)){
                $reflectionClass = new \ReflectionClass($ns);
                $items[$reflectionClass->getShortName()] = $reflectionClass->getName();
            }
        }
        return $items;
    }
}
