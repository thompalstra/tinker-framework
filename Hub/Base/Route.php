<?php
namespace Hub\Base;

use Frame;

class Route extends Base
{
    public static $prefixes = [];

    public function __construct($path, $parameters)
    {
        $this->setPath($path);
        $this->setParameters($parameters);
    }

    public static function get($route, $controller)
    {
        if(!empty(self::$prefixes)){
            $prefixes = implode("/", self::$prefixes);
            if(empty($route)){
                $route = $prefixes;
            } else {
                $route = implode("/", [$prefixes, $route]);
            }
        }
        Frame::$app->routes["get"][$route] = $controller;
    }

    public static function post($route, $controller)
    {
        Frame::$app->routes["post"][$route] = $controller;
    }

    public static function matches(array $types, $route, $controller)
    {
        foreach($types as $type){
            Frame::$app->routes[$type][$route] = $controller;
        }
    }

    public static function group(array $options, $closure)
    {
        if(isset($options["prefix"])){
            self::$prefixes[] = $options["prefix"];
        }

        $closure();

        array_pop(self::$prefixes);
    }
}
