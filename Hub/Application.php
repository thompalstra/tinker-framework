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
    public $names = [
        "get" => [],
        "post" => [],
        "put" => []
    ];
    public $db;
    public $queues = [];

    public function __construct()
    {
        require_once("Frame.php");

        include ('autoload.php');

        Frame::$app = &$this;

        $this->root = dirname(__DIR__);

        $config = Frame::path([$this->root, 'config.php']);
        if(file_exists($config)){
            $this->config = (object) include($config);
        }

        $this->db = new PDO("mysql:host={$this->config->mysql['host']};dbname={$this->config->mysql['dbname']}", "{$this->config->mysql['user']}", "{$this->config->mysql['password']}");
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->controller = new Controller();

        foreach($this->config->renderers as $class => $ext){
            View::registerEngine($class, $ext);
        }
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

        $class = Frame::ns([$this->request->getControllerPath(), $controller]);

        if(class_exists("{$class}")){
            Frame::$app->controller = new $class();
            return Frame::$app->controller->run($method, $route[1]);
        } else {
            return Frame::$app->controller->error(new Exception("Not implemented", 501));
        }
    }
}
