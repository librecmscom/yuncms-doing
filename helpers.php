<?php

if (!function_exists('doing')) {
    /**
     * 记录用户动态
     * @param int $userId 动态发起人
     * @param string $action 动作 ['ask','answer',...]
     * @param string $sourceType 被引用的内容类型
     * @param int $sourceId 问题或文章ID
     * @param string $subject 问题或文章标题
     * @param string $content 回答或评论内容
     * @param int $referId 问题或者文章ID
     * @param int $referUserId 引用内容作者ID
     * @param null $referContent 引用内容
     * @return bool
     */
    function doing($userId, $action, $model, $modelId, $subject, $content = '', $referId = 0, $referUserId = 0, $referContent = null)
    {
        try {
            $doing = \yuncms\doing\models\Doing::create([
                'user_id' => $userId,
                'action' => $action,
                'model_id' => $modelId,
                'model' => $model,
                'subject' => $subject,
                'content' => strip_tags($content),
                'refer_id' => $referId,
                'refer_user_id' => $referUserId,
                'refer_content' => strip_tags($referContent),
            ]);
            return $doing != false;
        } catch (\Exception $e) {
            return false;
        }
    }
}
 