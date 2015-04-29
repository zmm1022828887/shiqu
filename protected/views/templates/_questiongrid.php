<?php
$criteria = new CDbCriteria;
$criteria->order = "update_time";
$criteria->limit = $limit;
$models = Question::model()->findAll($criteria);
if (empty($models)) {
    echo "暂无问题";
} else {
    echo "<ul class='index-list'>";
    foreach ($models as $key => $value) {
        ?> 
        <li>
            <a class="blank" href="<?php echo $this->createUrl("question", array("id" => $value->id)); ?>"  target="_blank" rel="tooltip" title="问题<?php echo $value->answer_id!=0 ? "已解决":"未解决";?>，在新窗口打开"><i class="<?php echo $value->answer_id!=0 ? "icon-checkmark-circle-2":"icon-question";?>"></i></a>
            <a href="<?php echo $this->createUrl("question", array("id" => $value->id)); ?>" title="<?php echo $value->title . " - " . $this->title; ?>"><?php echo $value->title; ?></a>                        
            <span class="info">
                <?php
                $answer = Answer::model()->find("question_id=:question_id order by create_time desc", array(":question_id" => $value->id));
                $userID = $answer == NULL ? $value->create_user : $answer->create_user;
                $desc = $answer == NULL ? "提问于 " . Comment::timeintval($value->create_time) : "回答于 " . Comment::timeintval($answer->create_time);
                ?>
                <a href="javascript::" class="user-label" data-id="<?php echo $userID; ?>"><?php echo User::getNameById($userID); ?></a> <?php echo $desc; ?><span class="dot">•</span><?php echo $value->answer_id!=0 ? "已解决":"未解决";?></span>
            <span class="stat"><?php echo $value->view_count; ?>浏览 / <?php echo Answer::model()->count("question_id=" . $value->id); ?>回答</span>
        </li>
        <?php
    }
    echo "</ul>";
}?>