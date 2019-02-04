<?php
namespace Hub;

use PDO;
use Exception;

use Frame;

use Hub\Base\Request;
use Hub\Base\Base;
use Hub\Base\Controller;
use Hub\Base\View;

class Application extends Base
{
    public $root;
    public $routes = [
        "get" => [],
        "post" => [],
        "put" => []
    ];
    public $db;
    public $queues = [];

    public function __construct()
    {
        require_once("Frame.php");
        Frame::$app = &$this;

        $this->root = dirname(__DIR__);

        $config = Frame::path([$this->root, 'config.php']);
        if(file_exists($config)){
            $this->config = (object) include($config);
        }

        $this->db = new PDO("mysql:host={$this->config->mysql['host']};dbname={$this->config->mysql['dbname']}", "{$this->config->mysql['user']}", "{$this->config->mysql['password']}");
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->controller = new Controller();


        View::registerEngine("Hub\Blade\Renderer@output", ["blade", "blade.php"]);
        View::registerEngine("Hub\Twig\Renderer@output", ["twig", "twig.php"]);
        View::registerEngine("Hub\Http\Renderer@output", ["html", "php"]);
    }

    public function run($request)
    {
        return $this->handle($this->parse($request));
    }

    public function parse($request)
    {
        if($request instanceof Request){
            $this->process($request);
            return $this->request->getRoute();
        } else {
            throw new Exception("Request must be of instance 'Hub\Base\Request'");
        }
    }

    public function process($request)
    {
        $this->request = $request;

        $config = $this->request->getRoutes();

        if(file_exists($config)){
            require("$config");
        } else {
            trigger_error("Missing required file '$config'", E_USER_ERROR);
        }
    }

    public function handle($route)
    {
        preg_match('/(.*)@(.*)/', $route[0], $matches);

        $controller = $matches[1];
        $method = $matches[2];

        $ns = Frame::ns([$this->request->getControllerPath(), $controller]);

        if(class_exists("{$ns}")){
            Frame::$app->controller = new $ns();
            Frame::$app->controller->run($method, $route[1]);
        } else {
            Frame::$app->controller->run("error", ['message' => "{$ns} does not exist"]);
        }
    }
}
