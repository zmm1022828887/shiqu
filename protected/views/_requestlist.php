<style>
    ul,li{padding:0; margin:0;}
    .ques-list{margin:10px 0;}
    .ques-list a{ text-decoration: none; cursor: pointer;}
    .ques-list h1{margin:0 0 4px 0; background:none;  font-size:16px;overflow:hidden;line-height:20px;padding-top: 8px;}  
    .ques-list h1 .time{display: inline-block; font-size: 14px; font-weight: normal;} 
    .ques-list .diary-header-left{ float: left; text-align: center;height: 60px; width: 60px;margin: 10px;margin-left: 0;}
    .ques-list .about-info .tag{margin-left:5px;}
    .ques-list .about-info .tag a{display: inline-block;padding:0 5px;color:#fff; }
    .ques-list  .int {float: left;width: 40px;margin: 0 20px 0 0;padding: 4px 0 0;text-align: center;}
    .ques-list  .int .ans_num {display: inline-block;width:40px;height: 40px;color: #fff;font-weight: bold;font-size: 14px;}
    .ques-list .con {float: left;width: 654px;}
    .ques-list .vote_num{padding-left:0;padding-right: 0;display:inline-block;width: 100%;text-align: center;margin-top: 4px;}
    .ques-list .con .time a{color:#ccc;display: none;}
</style>
<?php
$questionModel = Question::model()->findByPK($data->question_id);
?>
<div class="view request-item">
    <div class="ques-list clearfix">
        <dl class="int">
            <?php if ($_GET["type"] == "help") { ?>
                <?php
                $count = Answer::model()->count("create_user=:create_user",array(":create_user"=>$data->create_user));
                ?>
                <dt class="ans_num"><a data-id="<?php echo $data->create_user; ?>" class="user-label"><img height="40" width="40" src="<?php echo $this->createUrl("getimage", array("id" => $data->create_user, "type" => "avatar")); ?>"></a></dt>
                <dd><span class="vote_num label" title="<?php echo $count . "个回答"; ?>"><?php echo $count; ?></span></dd>
            <?php } else { ?>
                <?php
                $count = Answer::model()->count("create_user=:create_user",array(":create_user"=>$data->to_user));
                ?>
                <dt class="ans_num"><a data-id="<?php echo $data->to_user; ?>" class="user-label"><img height="40" width="40" src="<?php echo $this->createUrl("getimage", array("id" => $data->to_user,"type" => "avatar")); ?>"></a></dt>
                <dd><span class="vote_num label" title="<?php echo $count . "个回答"; ?>"><?php echo $count; ?></span></dd>
            <?php } ?>
        </dl>
        <div class="con">
            <div class="diary-header clearfix">
                <?php if ($_GET["type"] == "help") { ?>
                    <p class="clearfix " style="font-size: 12px;height: 24px;line-height: 24px;"><span class="pull-left"><a href='<?php echo $this->createUrl("userinfo", array("user_id" => $data->create_user)); ?>' class="user-label" data-id="<?php echo $data->create_user; ?>" title="<?php echo User::getNameById($data->create_user); ?>"><?php echo User::getNameById($data->create_user); ?></a><span style="color:#ccc;margin-left: 10px;"><?php echo Comment::timeintval($data->create_time); ?> 向你咨询</span></span><span class="pull-right time"><a data-action="delete" data-requestid="<?php echo $data->id;?>">忽略</a></span></p>
                <?php } else { ?>
                    <p class="clearfix " style="font-size: 12px;height: 24px;line-height: 24px;"><span class="pull-left"><a href='<?php echo $this->createUrl("userinfo", array("user_id" => $data->to_user)); ?>' class="user-label" data-id="<?php echo $data->to_user; ?>" title="<?php echo User::getNameById($data->to_user); ?>"><?php echo User::getNameById($data->to_user); ?></a><span style="color:#ccc;margin-left: 10px;"><?php echo Comment::timeintval($data->create_time); ?> 被你询问</span></span><span class="pull-right time"><a data-action="delete" data-requestid="<?php echo $data->id;?>">取消咨询</a></span></p>
                <?php } ?>

                <h1> <?php echo CHtml::link($questionModel->title, $this->createUrl("question", array("id" => $questionModel->id, "action" => "view")), array("title" => $questionModel->title)); ?></h1>                
                <div class="diary-content"><?php echo strlen(strip_tags($questionModel->content)) > 500 ? (mb_strcut(strip_tags($questionModel->content), 0, 500, 'utf-8')) . "..." : strip_tags($questionModel->content); ?></div>
                <?php $answerModel = Answer::model()->find("question_id=:question_id order by create_time desc", array(":question_id" => $questionModel->id)); ?>
                <?php if ($answerModel != NULL) { ?>
                    <p class="clearfix " style="font-size: 12px;height: 24px;line-height: 24px;"><span class="pull-left"><a href='<?php echo $this->createUrl("userinfo", array("user_id" => $answerModel->create_user)); ?>' class="user-label" data-id="<?php echo $answerModel->create_user; ?>" title="<?php echo User::getNameById($answerModel->create_user); ?>"><?php echo User::getNameById($answerModel->create_user); ?></a><span style="color:#ccc;margin-left: 10px;"><?php echo Comment::timeintval($answerModel->create_time); ?> 回答</span></span><span class="pull-right"><a class="user-label" href="javascript:;" data-id="<?php echo $answerModel->create_user; ?>"><img style="height: 24px;width: 24px;vertical-align:top;border-radius: 2px;" height="24" width="24" src="<?php echo $this->createUrl("getimage", array("id" => $answerModel->create_user, "type" => "avatar")); ?>"></a></span></p>
                    <?php echo $answerModel->content; ?>
                    <?php $commentCount = Comment::model()->count("pk_id=:pk_id and model=:model and parent_id=0 order by create_time desc", array(":model" => "answer", ":pk_id" => $answerModel->id)); ?>
                    <p class="clearfix" style="font-size: 12px;padding-top: 5px;color:#ccc;"><a href="<?php echo $this->createUrl("answer", array("id" => $answerModel->id)); ?>" title="<?php echo $commentCount; ?>条评论"><i class="icon-bubble-dots-4" style="margin-right: 4px;"></i><?php echo $commentCount; ?></a>
                        <span class="pull-right">
                            <?php
                            if ($commentCount > 0) {
                                $commentModel = Comment::model()->find("pk_id=:pk_id and model=:model and parent_id=0 order by create_time desc", array(":model" => "answer", ":pk_id" => $answerModel->id));
                                ?>
                                <a class="user-label" href="javascript:;" data-id="<?php echo $commentModel->user_id; ?>"><?php echo User::getNameById($commentModel->user_id); ?></a>
                                <?php echo Comment::timeintval($commentModel->create_time) . "评论"; ?>
                                <?php
                            } else {
                                echo "暂无评论";
                            }
                            ?>
                        </span>
                    </p>
                <?php } ?>
            </div>
        </div>
    </div>
</div>