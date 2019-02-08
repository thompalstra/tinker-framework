<?php

namespace Hub\Base;

use Hub\Base\Base;

class Widget extends Base
{
    public static function widget($options = [])
    {
        $class = self::getClass();
        $widget = new $class();
        $widget->prepare($options);
        return $widget->execute();
    }

    public function prepare()
    {

    }

    public function execute()
    {

    }
}
