<?php
namespace App\Cli\Controllers;

use Frame;

use Hub\Queue\Manager;
use Hub\Queue\Queue;
use Hub\Queue\Worker;

class QueueController extends \Hub\Base\Controller
{
    public function start(string $name, int $async = 1)
    {
        $manager = new Manager();
        Frame::$app->queues[$name] = $manager;
        Frame::$app->queues[$name]->start($name, $async);
    }

    public function random()
    {
        return [
            'class' => '\App\Task',
            'method' => 'execute',
            'parameters' => [ rand(0, 200) ]
        ];
    }
}
