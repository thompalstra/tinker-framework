<?php
namespace Hub\Base;

class Base
{
    public function __get($key)
    {
        return $this->$key;
    }

    public function __set($key, $value)
    {
        $this->$key = $value;
    }

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

    public static function __callStatic($method, $arg)
    {
        if(method_exists(self::class, $method)){
            call_user_func_array([self::class, $method], $arg);
        } else {
            echo "not implemented __callStatic"; exit;
        }
    }
}
