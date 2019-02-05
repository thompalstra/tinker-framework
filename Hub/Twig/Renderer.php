<?php
namespace Hub\Twig;

use Frame;

use Twig_Loader_Filesystem;
use Twig_Environment;

class Renderer extends \Hub\Base\Base
{
    public static function output($dir, $fp, $data)
    {
        $twig = new Twig_Environment(
            self::getLoader($dir),
            self::getEnvOptions()
        );

        return $twig->render($fp, $data);
    }

    public static function getLoader($dir)
    {
        return new Twig_Loader_Filesystem($dir);
    }

    public static function getEnvOptions()
    {
        $options = [];

        $options['cache'] = self::getCacheDir();

        return $options;
    }

    public static function getCacheDir()
    {
        return Frame::path(['storage', 'cache', 'twig', 'compilation_cache']);
    }
}
