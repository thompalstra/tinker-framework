<?php
namespace Hub\Base;

use Frame;

class View extends Base
{
    public static $engines = [];

    /**
     * Registers a new rendering engine
     *
     * View::registerEngine('Hub\Http\Renderer@output', ['html', 'php'])
     *
     * @param string $engine The engine to use
     * @param array $extensions The file extensions the engine supports
     */
    public static function registerEngine(string $engine = "test", array $extensions = [])
    {
        self::$engines[$engine] = $extensions;
    }

    /**
     * Returns a collection of registered renderer engines
     *
     * @return array $engines
     */
    public static function getEngines()
    {
        return self::$engines;
    }

    /**
     * Outputs a view and layout based on the controller's layout and
     * provided view '$name' and exits the script.
     *
     * View::render('index', array('username' => 'admin'))
     *
     * @param string $name The view to render
     * @param array $data The data to pass to the view
     * @return html
     */
    public static function render(string $name, array $data = [])
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

    /**
     * Outputs a single view based on the view '$name'
     *
     * View::make('contact', array('myvar'=>'myval'))
     *
     * @param string $name The view to render
     * @param array $data The data to pass to the view
     */
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
