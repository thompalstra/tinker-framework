<?php
namespace Hub\Queue;

use Hub\Base\Model;

class Queue extends Model
{
    public static $columns = [
        'id' => 'PRIMARY_KEY AUTO_INCREMENT',
        'queue' => 'VARCHAR(255) NOT NULL',
        'workers' => 'INT(1) NOT NULL',
        'updated_at' => 'INT(11)',
        'created_at' => 'INT(11)',
    ];

    public function next()
    {
        return Job::findOne([
            ['queue', '=', $this->queue],
            ['status', '=', \App\Job::STATUS_PENDING]
        ]);
    }

    public function hasNext()
    {
        return Job::find()->where([
            ['queue', '=', $this->queue],
            ['status', '=', \App\Job::STATUS_PENDING]
        ])->count() > 0;
    }

    public function count()
    {
        return Job::find()->where([
            ['queue', '=', $this->queue],
            ['status', '=', \App\Job::STATUS_PENDING]
        ])->count();
    }
}
