<?php
namespace Hub\Base;

use Frame;

class Route extends Base
{
    public static $prefixes = [];
    public static $names = [];

    /**
     * Constructs the route based on the provided path '$path' and parameters '$parameters'
     *
     * @param string $path The path to use
     * @param array $parameters The parameters to use
     */
    public function __construct($path, $parameters, $method = 'get')
    {
        $this->setPath($path);
        $this->setParameters($parameters);
        $this->setMethod($method);
    }

    public static function to($name, array $params = [])
    {
        foreach(Frame::$app->names as $type => $values){
            if(isset(Frame::$app->names[$type][$name])){
                $route = Frame::$app->names[$type][$name];

                foreach($params as $key => $value){
                    $route = str_replace('{' . $key . '}', $value, $route);
                }

                if(strpos($route, '{') > -1){
                    Frame::$app->controller->error("Missing required parameters for route {$name}");
                }

                return $route;
            }
        }
    }

    /**
     * Registers a new GET route based on the provided arguments
     *
     * @param string $route The route to use for matching
     * @param string $controller The controller to use when matchd
     */
    public static function get($route, $controller, array $options = [])
    {
        if(!empty(self::$prefixes)){
            $prefixes = implode("/", self::$prefixes);
            if(empty($route)){
                $route = $prefixes;
            } else {
                $route = implode("/", [$prefixes, $route]);
            }
        }

        if(isset($options['name'])){
            $names = self::$names;
            $names[] = $options['name'];
            $name = implode("", $names);

            if(!empty($name)){
                Frame::$app->names["get"][$name] = $route;
            }
        }
        Frame::$app->routes["get"][$route] = $controller;
    }

    /**
     * Registers a new POST route based on the provided arguments
     *
     * @param string $route The route to use for matching
     * @param string $controller The controller to use when matchd
     */
    public static function post($route, $controller)
    {
        if(!empty(self::$prefixes)){
            $prefixes = implode("/", self::$prefixes);
            if(empty($route)){
                $route = $prefixes;
            } else {
                $route = implode("/", [$prefixes, $route]);
            }
        }

        if(!empty(self::$names) && isset($options['name'])){
            $names = self::$names;
            $names[] = $options['name'];
            $name = implode("", $names);

            if(!empty($name)){
                Frame::$app->names["get"][$name] = $route;
            }
        }

        Frame::$app->routes["post"][$route] = $controller;
    }

    /**
     * Registers multiple new routes based on the provided arguments
     *
     * @param string $route The route to use for matching
     * @param string $controller The controller to use when matchd
     */
    public static function matches(array $types, $route, $controller)
    {
        foreach($types as $type){
            self::$type($route, $controller);
        }
    }

    /**
     * Create a new group used for matching
     *
     * @param string $route The route to use for matching
     * @param string $controller The controller to use when matchd
     */
    public static function group(array $options, $closure)
    {
        if(isset($options["prefix"])){
            self::$prefixes[] = $options["prefix"];
        }
        if(isset($options["name"])){
            self::$names[] = $options["name"];
        }

        $closure();

        array_pop(self::$prefixes);
        array_pop(self::$names);
    }
}
