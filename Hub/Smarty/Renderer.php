<?php
namespace Hub\Smarty;

use Frame;

use Smarty;

class Renderer extends \Hub\Base\Base
{
    public static function output($dir, $fp, $data)
    {
        $smarty = new Smarty();
        $smarty->setTemplateDir($dir);
        $smarty->setCompileDir(Frame::path(['storage', 'cache', 'smarty', 'compilation_cache']));
        $smarty->setCacheDir(Frame::path(['storage', 'cache', 'smarty', 'cache']));

        $smarty->caching = true;

        $smarty->assign($data);

        $path = Frame::path([$dir, $fp]);

        $smarty->display("{$path}");
    }
}
