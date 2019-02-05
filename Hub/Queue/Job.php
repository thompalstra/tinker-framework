<?php

namespace Hub\Queue;

use Hub\Base\Model;

class Job extends Model
{
    const STATUS_PENDING = 0;
    const STATUS_WORKING = 1;
    const STATUS_COMPLETED = 2;
    const STATUS_FAILED = 3;

    protected $status = 0;

    public static $columns = [
        'id' => 'PRIMARY_KEY AUTO_INCREMENT',
        'queue' => 'VARCHAR(255) NOT NULL',
        'status' => 'INT(11) NOT NULL',
        'task' => 'VARCHAR(255) NOT NULL',
        'arguments' => 'TEXT NOT NULL',
        'updated_at' => 'INT(11)',
        'created_at' => 'INT(11)',
    ];

    public function setPending()
    {
        $this->status = self::STATUS_PENDING;
        $this->save();
    }

    public function setWorking()
    {
        $this->status = self::STATUS_WORKING;
        $this->save();
    }

    public function setArguments($arguments)
    {
        $this->arguments = json_encode($arguments);
    }

    public function getArguments()
    {
        return json_decode($this->arguments, true);
    }

    public function setFailed()
    {
        $failedJob = new FailedJob();
        $failedJob->queue = $this->queue;
        $failedJob->task = $this->task;
        $failedJob->arguments = $this->arguments;
        $failedJob->message = $this->message;
        $failedJob->updated_at = time();
        $failedJob->created_at = time();
        if($failedJob->save()){
            return $this->delete();
        } else {
            throw new \Exception("Unable to save!"); exit;
        }
    }

    public function setCompleted()
    {
        $completedJob = new CompletedJob();
        $completedJob->queue = $this->queue;
        $completedJob->task = $this->task;
        $completedJob->arguments = $this->arguments;
        $completedJob->updated_at = time();
        $completedJob->created_at = time();
        if($completedJob->save()){
            return $this->delete();
        } else {
            throw new \Exception("Unable to save!"); exit;
        }
    }
}
