<?php
namespace Hub\Queue;

use Hub\Base\Base;

class Manager extends Base
{
    protected $workers = [];
    protected $available_count = 1;

    protected $working = 0;

    protected $async = true;

    protected $completed = 0;
    protected $total = 0;

    public function __construct()
    {
    }

    public function addToQueue($data)
    {
        $this->queue->add($data);
    }

    public function count()
    {
        return count($this->workers);
    }

    public function dispatch($job)
    {
        $job->status = Job::STATUS_WORKING;
        $job->save();

        $this->working++;
        $storage = new \Threaded();
        $worker = new Worker($storage);
        $worker->job = $job;
        $storage['payload'] = $job->arguments;
        $this->workers[] = $worker;
        $worker->start();
        if(!$this->async){
            $worker->join();
        }
    }

    // public function fail($worker)
    // {
    //     $failedJob = new FailedJob();
    //     $failedJob->queue = $worker->job->queue;
    //     $failedJob->task = $worker->job->task;
    //     $failedJob->arguments = $worker->storage['payload'];
    //     $failedJob->message = $worker->message;
    //     $failedJob->updated_at = time();
    //     $failedJob->created_at = time();
    //     if($failedJob->save()){
    //         return $worker->job->delete();
    //     } else {
    //         throw new \Exception("Unable to save!"); exit;
    //     }
    // }
    //
    // public function complete($worker)
    // {
    //     $completedJob = new CompletedJob();
    //     $completedJob->queue = $worker->job->queue;
    //     $completedJob->task = $worker->job->task;
    //     $completedJob->arguments = $worker->storage['payload'];
    //     $completedJob->updated_at = time();
    //     $completedJob->created_at = time();
    //     if($completedJob->save()){
    //         return $worker->job->delete();
    //     } else {
    //         throw new \Exception("Unable to save!"); exit;
    //     }
    // }

    public function next()
    {
        if($this->queue->hasNext()){
            if($this->count() < $this->available_count){
                while($this->count() < $this->available_count){
                    if(!$this->queue->hasNext()){
                        break;
                    }
                    $this->dispatch($this->queue->next());
                }
            }
        }
    }

    public function start($queue, $async)
    {
        $type = ($async ? "'async'" : "'sync'");
        echo "Starting as {$type}\n";

        $this->queue = Queue::findOne([
            ['queue', '=', $queue]
        ]);

        if($this->queue){
            $this->async = $async;
            $this->available_count = $this->queue->workers;
            $this->async = $async;
            $this->time = time();

            while(true){
                foreach($this->workers as $index => $worker){
                    if($worker->isDone()){

                        $job = $worker->job;

                        if($worker->isCompleted()){
                            echo "Completed {$worker->job->id}\n";

                            $job->arguments = $worker->storage['payload'];
                            $job->setCompleted($worker);
                            // $this->complete($worker);
                        } else if($worker->isFailed()){
                            echo "Failed {$worker->job->id}\n";
                            $job->arguments = $worker->storage['payload'];
                            $job->message = $worker->message;
                            $job->setFailed($worker);
                            // $this->fail($worker);
                        }
                        $worker = null;
                        $this->completed++;
                        unset($this->workers[$index]);
                    }
                }

                if($this->queue->hasNext()){
                    if($this->count() < $this->available_count){
                        while($this->count() < $this->available_count){
                            if(!$this->queue->hasNext()){
                                break;
                            }
                            $this->dispatch($this->queue->next());
                        }
                    }
                }
            }
        } else {
            echo "Missing record for queue '{$queue}'... aborting.\n"; exit;
        }
    }
}
