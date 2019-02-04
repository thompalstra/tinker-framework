<?php
namespace App\Http\Controllers;

use Hub\Base\Controller;

class QueueController extends Controller
{
    public function request($count = 1)
    {
        for($i = 0; $i < $count; $i++){
            $job = new \App\Job();
            $job->task = "App\Tasks\Base@execute";
            $job->queue = 'default';
            $job->arguments = json_encode(["this" => "that"]);
            $job->created_at = time();
            $job->updated_at = time();
            $job->save();
        }
    }
}
