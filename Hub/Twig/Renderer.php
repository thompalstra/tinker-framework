<?php
namespace Hub\Twig;

use Frame;

use Twig_Loader_Filesystem;
use Twig_Environment;

class Renderer extends \Hub\Base\Base
{
    public static function output($dir, $fp, $data)
    {
        $twig = new Twig_Environment(new Twig_Loader_Filesystem($dir), [
            'cache' => Frame::path(['storage', 'cache', 'twig', 'compilation_cache'])
        ]);
        echo $twig->load($fp)->render($data);
    }
}
