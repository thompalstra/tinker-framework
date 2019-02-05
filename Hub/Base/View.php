<?php
namespace Hub\Base;

use Frame;

class View extends Base
{

    // public static $extensions = ["php", "html", "php.blade", "blade", "twig"];

    public static $engines = [];

    public static function registerEngine($engine, $method)
    {
        self::$engines[$engine] = $method;
    }

    public static function getEngines()
    {
        return self::$engines;
    }

    public static function render($name, array $data = [])
    {
        $viewPath = Frame::$app->request->getViewPath();
        $layoutPath = Frame::$app->request->getLayoutPath();

        $params = [];
        if(!empty($layoutPath)){
            $params[] = $layoutPath;
        }
        $params[] =  Frame::$app->controller->getLayout();

        $layout = Frame::path($params);

        $params = [];
        if(!empty($viewPath)){
            $params[] = $viewPath;
        }
        $params[] = $name;

        $view = Frame::path($params);


        echo self::make($layout, [
            "content" => self::make($view, $data)
        ]);
        exit();
    }

    public static function make($name, $data = [])
    {
        $dir = Frame::path(['storage', 'views']);
        $name = str_replace(["\\", "/"], DIRECTORY_SEPARATOR, $name);
        foreach(self::getEngines() as $renderer => $extensions){
            foreach($extensions as $extension){
                $path = Frame::path([$dir, $name]);
                if(file_exists("{$path}.{$extension}")){
                    preg_match('/(.*)@(.*)/', $renderer, $matches);

                    $class = $matches[1];
                    $method = $matches[2];

                    return call_user_func_array([$class, $method], [$dir, "{$name}.{$extension}", $data]);
                }
            }
        }
        echo "{$dir}\\{$name}";
        return "unable to render file: '{$name}'";
    }
}
