<?php
namespace Hub\Base;

use Frame;

class Request extends Base implements RequestInterface
{
    protected $path = "";
    protected $parameters = [];

    /**
     * Convert the provided options '$options' to a path and argument array
     *
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $this->process($options);
    }

    /**
     * Placeholder for the 'process' method - each request should extend from this
     *
     * @param array $options
     */
    public function process(array $options = [])
    {
        $class = self::class;
        trigger_error("Call to undefined method {$class}::process", E_USER_ERROR);
    }

    /**
     * Adds a single parameter to the current available set to be used later
     * for matching in the Controller class
     *
     * $this->addParameter('users', [12,34,55])
     *
     * @param string $key The key to use
     * @param mixed $value The value to set
     */
    public function addParameter($key, $value)
    {
        $this->parameters[$key] = $value;
    }

    /**
     * Returns the matching route based on this method, path and parameters
     *
     *
     * @return array [ string "controller@method", array $parameters ];
     */
    public function getRoute()
    {
        foreach(Frame::$app->routes[$this->getMethod()] as $path => $controller){
            if($this->matchRoute($this->getPath(), $path)){
                return [Frame::path([$controller]), $this->getParameters()];
            }
        }
        return ["HomeController@error", ["Route does not exist"]];
    }

    /**
     * Returns if the provided source '$source' path matches the target '$target'
     *
     * $this->matchRoute('/about/contact', '/about/{which}') // returns true
     * $this->matchRoute('/about/contact', '/about/contact') // returns true
     * $this->matchRoute('/about/contact', '/about/{which}/info') // returns false
     *
     * @param string $source The current source
     * @param string $target The current target
     * @return boolean
     */
    public function matchRoute($source, $target)
    {
        $sourceParts = explode("/", $source);
        $targetParts = explode("/", $target);
        $i = 0;
        $params = [];

        if(count($sourceParts) == count($targetParts)){
            foreach($targetParts as $index => $targetPart){
                preg_match('/{(.*)}/', $targetPart, $matches);
                if(count($matches) > 0){
                    $attribute = $matches[1];
                    $params[$attribute] = $sourceParts[$index];
                    $i++;
                } else if($targetPart == $sourceParts[$index]){
                    $i++;
                }
            }

            if(count($targetParts) == $i){
                foreach($params as $key => $param){
                    $this->addParameter($key, $param);
                }
                return true;
            }
        }
        return null;
    }
}
