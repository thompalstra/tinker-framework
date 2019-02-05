<?php
namespace Hub\Smarty;

use Frame;

use Smarty;

class Renderer extends \Hub\Base\Base
{
    public static function output($dir, $fp, $data)
    {
        $smarty = new Smarty();

        $smarty->caching = true;

        return $smarty->setTemplateDir($dir)
            ->setCompileDir(self::getCompileDir())
            ->setCacheDir(self::getCacheDir())
            ->assign($data)
            ->fetch(Frame::path([$dir, $fp]));
    }

    public static function getCompileDir()
    {
        return Frame::path(['storage', 'cache', 'smarty', 'compilation_cache']);
    }

    public static function getCacheDir()
    {
        return Frame::path(['storage', 'cache', 'smarty', 'cache']);
    }
}
