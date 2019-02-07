<?php
namespace Hub\Base;

use Exception;
use ReflectionMethod;

use Hub\Base\View;

class Controller extends Base
{
    protected $layout = "main";

    /**
     * Runs a 404 error
     *
     * @param string $message message to be displayed
     */
    public function error($exception)
    {
        http_response_code($exception->getCode());
        View::render('error', ['exception' => $exception]);
    }

    /**
     * Runs the provided 'method' with its arguments
     *
     * $productController->run('view', array(453, 2));
     * public function view($productId, $categoryId) { ... }
     *
     * @param string $method method to call like 'index' or 'contact'
     * @param array $arg array of arguments to apply to the method
     * @param string html result
     */
    public function run($method, $arg)
    {
        if(method_exists($this, $method)){
            $params = $this->parameters($method, $arg);
            return call_user_func_array([$this, $method], $params);
        } else {
            $class = get_called_class();
            return Frame::$app->controller->error(new Exception("Not implemented", 501));
        }
    }

    /**
     * Checks to see if the provided arguments '$arg' matches the required variables
     * available in the provided method '$method'
     *
     * @param string $method The method to call like 'index' or 'contact'
     * @param array $arg An array of arguments available
     * @return array $params An array of arguments to apply to the method
     */
    public function parameters($method, $arg)
    {
        $reflectionMethod = new ReflectionMethod($this, $method);
        $params = [];
        foreach($reflectionMethod->getParameters() as $param){
            $paramType = null;
            if(method_exists($param, "getType")){
                $paramType = $param->getType();
            }
            $paramName = $param->getName();
            $isOptional = $param->isOptional();
            $paramTypeName = ($paramType) ? $paramType->getName() : null;
            $paramDefaultValue = $param->isDefaultValueAvailable() ? $param->getDefaultValue() : null;

            if(isset($arg[$paramName])){
                $params[] = $arg[$paramName];
            } elseif($isOptional && isset($paramDefaultValue)){
                $params[] = $paramDefaultValue;
            } else {
                return $this->error(new Exception("Unprocessable Entity", 422));
            }
        }
        return $params;
    }

    /**
     * Redirects the client and exits the current script
     *
     * @param string $url the url to redirect to
     */
    public function redirect($url)
    {
        header("Location: {$url}"); exit;
    }
}
