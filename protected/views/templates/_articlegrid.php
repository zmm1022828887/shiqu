<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$criteria = new CDbCriteria;
$criteria->order = "update_time";
$criteria->limit = $limit;
$criteria->addCondition("publish=1");
$models = Article::model()->findAll($criteria);
if (empty($models)) {
    echo "暂无文章";
} else {
    echo "<ul class='index-list'>";
    foreach ($models as $key => $value) {
        ?> 
        <li>
            <a class="blank" href="<?php echo $this->createUrl("article", array("id" => $value->id)); ?>"  target="_blank" rel="tooltip" title="在新窗口打开"><i class="icon-file-4"></i></a>
            <a href="<?php echo $this->createUrl("article", array("id" => $value->id)); ?>" title="<?php echo $value->subject . " - " . $this->title; ?>"><?php echo $value->subject; ?></a>                        
            <span class="info">
                <?php
                $comment = Comment::model()->find("pk_id=:pk_id and model='article' order by create_time desc", array(":pk_id" => $value->id));
                $userID = $comment == NULL ? $value->create_user : $comment->user_id;
                $desc = $comment == NULL ? "发表于 " . Comment::timeintval($value->create_time) : "评论于 " . Comment::timeintval($comment->create_time);
                ?>
                <a href="javascript::" class="user-label" data-id="<?php echo $userID; ?>"><?php echo User::getNameById($userID); ?></a> <?php echo $desc; ?></span>
            <span class="stat"><?php echo $value->view_count; ?>浏览 / <?php echo Comment::model()->count(" model='article' and pk_id=" . $value->id); ?>评论</span>
        </li>
        <?php
    }
    echo "</ul>";
}
?>