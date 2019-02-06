<?php
namespace Hub\Base;

class Base
{
    /**
     * Overrides the '__get' method
     *
     * @param string $key
     * @return mixed
     */
    public function __get(string $key)
    {
        return $this->$key;
    }

    /**
     * Overrides the '__set' method
     *
     * @param string $key
     */
    public function __set(string $key, $value)
    {
        $this->$key = $value;
    }

    /**
     * Overrides the '__call' function
     *
     * @param string $method
     * @param array $arg
     * @return mixed
     */
    public function __call($method, $arg)
    {
        if(strlen($method) > 3) {
            $attr = strtolower(preg_replace('/\B([A-Z])/', '_$1', substr($method, 3, strlen($method))));
            switch(substr($method, 0, 3)){
                case "get":
                    if(property_exists($this, $attr)){
                        return $this->$attr;
                    }
                    return null;
                break;
                case "set":
                    $this->$attr = $arg[0];
                break;
            }
        } else if(method_exists($this, $method)){
            call_user_func_array([$this, $method], $arg);
        }
    }

    /**
     * Overrides the '__get' method
     *
     * @param string $method
     * @param array $arguments
     * @return mixed
     */
    public static function __callStatic($method, $arguments)
    {
        if(method_exists(self::class, $method)){
            call_user_func_array([self::class, $method], $arguments);
        } else {
            echo "not implemented __callStatic"; exit;
        }
    }
}
