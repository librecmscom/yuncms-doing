<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\doing\jobs;

use yii\base\BaseObject;
use yii\queue\Queue;
use yii\queue\RetryableJobInterface;
use yuncms\doing\models\Doing;

/**
 * Class DoingJob
 * @package yuncms\doing\jobs
 */
class DoingJob extends BaseObject implements RetryableJobInterface
{
    public $user_id;
    public $action;
    public $model_id;
    public $model;
    public $subject;
    public $content = '';
    public $refer_id = 0;
    public $refer_user_id = 0;
    public $refer_content = null;

    /**
     * @param Queue $queue which pushed and is handling the job
     */
    public function execute($queue)
    {
        Doing::create([
            'user_id' => $this->user_id,
            'action' => $this->action,
            'model_id' => $this->model_id,
            'model' => $this->model,
            'subject' => $this->subject,
            'content' => strip_tags($this->content),
            'refer_id' => $this->refer_id,
            'refer_user_id' => $this->refer_user_id,
            'refer_content' => strip_tags($this->refer_content),
        ]);
    }

    /**
     * @return int time to reserve in seconds
     */
    public function getTtr()
    {
        return 60;
    }

    /**
     * @param int $attempt number
     * @param \Exception|\Throwable $error from last execute of the job
     * @return bool
     */
    public function canRetry($attempt, $error)
    {
        return $attempt < 3;
    }
}