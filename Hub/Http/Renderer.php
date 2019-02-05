<?php
namespace Hub\Http;

use Frame;

class Renderer extends \Hub\Base\Base
{
    public static function output($dir, $fp, $data)
    {
        $fp = Frame::path([$dir, $fp]);
        extract($data);
        ob_start();
        include($fp);
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }
}
