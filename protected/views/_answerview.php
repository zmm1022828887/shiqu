<style>
    .answer-item{border-bottom: 1px dashed #ccc;padding-top: 10px;padding-bottom: 10px;}
    .vote-number .btn-mini {
        width: 30px;
        padding: 0;

    }
</style>
<div class="answer-item">
    <?php $model = Question::model()->findByPk($data->question_id); ?>
    <div class="question-body clearfix">
        <div class="pull-left vote-number clearfix">
            <div class="pull-left opt-list" style="width: 50px;"> <a  rel="tooltip" data-original-title="<?php echo Vote::model()->count("opinion=0 and model=:model and pk_id=:pk_id and create_user=:create_user", array(":create_user" => Yii::app()->user->id, ":pk_id" => $model->id, ":model" => "question")) > 0 ? '取消赞同' : '赞同'; ?>"  class="btn btn-mini <?php echo Vote::model()->count("opinion=0  and pk_id=:pk_id and create_user=:create_user and model=:model", array(":model" => "question", ":create_user" => Yii::app()->user->id, ":pk_id" => $model->id)) > 0 ? 'active' : ''; ?>" name="<?php echo (Yii::app()->user->isGuest) ? 'noLogin' : 'agreeeAnswer'; ?>" data-model="question" data-pk="<?php echo $model->id; ?>"><i class="icon icon-arrow-up"></i><br/><span class="count"><?php echo Vote::model()->count("opinion=0 and model=:model and pk_id=:pk_id", array(":model" => "question", ":pk_id" => $model->id)); ?></span></a> <br/><a class="btn btn-mini <?php echo Vote::model()->count("opinion=1 and model=:model and pk_id=:pk_id and create_user=:create_user", array(":model" => "question", ":create_user" => Yii::app()->user->id, ":pk_id" => $model->id)) > 0 ? 'active' : ''; ?>" rel="tooltip" data-original-title="<?php echo Vote::model()->count("opinion=1 and pk_id=:pk_id and model=:model and create_user=:create_user", array(":create_user" => Yii::app()->user->id, ":pk_id" => $model->id, ":model" => "question")) > 0 ? '取消反对' : '反对，不会显示你的姓名'; ?>"  data-placement="bottom" name="<?php echo (Yii::app()->user->isGuest) ? 'noLogin' : 'disagreeeAnswer'; ?>"   data-model="question" data-pk="<?php echo $model->id; ?>"><i style="font-size:14px;" class="icon icon-arrow-down"></i></a></div>
        </div>
        <div class="question-content" style="margin-left:50px;">
           <p><?php $topicArray = explode(",",trim($model->topic_ids,","));?><?php for($i=0;$i<count($topicArray);$i++){?>
                 <?php  $topicModel = Topic::model()->findByPk($topicArray[$i]);?>
                    <?php if($topicModel){?>
                <a href="javascropt:;" data-id="<?php echo $topicArray[$i];?>" class="topic-label"><span class="label"><?php echo $topicModel->name;?><span></a>
                    <?php }?>
                <?php }?>           
                 <a  href="javascript:;" onclick="report(<?php echo $model->id;?>, 'question')" title="举报" class="pull-right"><i class="icon-flag" style="margin-right: 4px;"></i> 举报</a></p>
            <h3><a href="<?php echo $this->createUrl("question", array("id" => $model->id)); ?>" title="<?php echo $model->title; ?>"><?php echo $model->title; ?></a></h3>
                                 <div class="vote-info">
                        <?php $voteOpinion = Vote::model()->findAll("pk_id = :pk_id and model=:model and opinion=0 order by create_time desc", array(":pk_id" => $model->id, ":model" => "question")); ?>
                        <?php if (count($voteOpinion) > 0) { ?>
                            <span class="voters vote-some">
                                <?php
                                $i = 0;
                                foreach ($voteOpinion as $key => $value) {
                                    $i++;
                                    if ($i == 4)
                                        break;
                                    echo ((($i == 3) && (count($voteOpinion)) >= 3) || (($i == 2) && (count($voteOpinion)) == 2) || (($i == 1) && (count($voteOpinion) == 1))) ? "<a class='user-label' href='javascropt:;' data-id='" . $value->create_user . "' data-value='" . $value->create_user . "'>" . User::getNameById($value->create_user) . "</a>" : "<a data-value='" . $value->create_user . "' class='user-label' href='javascropt:;' data-id='" . $value->create_user . "' data-value='" . $value->create_user . "'>" . User::getNameById($value->create_user) . "</a>" . "<span>、</span>";
                                }
                                ?>
                            </span>
                            <span class="voters vote-all" style="display:none;">
                                <?php
                                $j = 0;
                                foreach ($voteOpinion as $key => $value) {
                                    $j++;
                                    echo ($j == count($voteOpinion)) ? "<a class='user-label' href='javascropt:;' data-id='" . $value->create_user . "' data-value='" . $value->create_user . "'>" . User::getNameById($value->create_user) . "</a>" : "<a data-value='" . $value->create_user . "' class='user-label' href='javascropt:;' data-id='" . $value->create_user . "' data-value='" . $value->create_user . "'>" . User::getNameById($value->create_user) . "</a>" . "<span>、</span>";
                                }
                                ?>
                            </span>
                            <a href="javascript:;" class="<?php echo (count($voteOpinion) >= 4 ? 'all' : 'some'); ?>"><?php echo (count($voteOpinion) >= 4 ? '等人赞同' : '赞同'); ?></a>
                        <?php } ?>
                 </div>
            <p class="clearfix " style="font-size: 12px;height: 24px;line-height: 24px;"><span class="pull-left"><a href='<?php echo $this->createUrl("userinfo", array("user_id" => $data->create_user)); ?>' class="user-label" data-id="<?php echo $data->create_user; ?>" title="<?php echo User::getNameById($data->create_user); ?>"><?php echo User::getNameById($data->create_user); ?></a><span style="color:#ccc;margin-left: 10px;"><?php echo Comment::timeintval($data->create_time); ?> 回答</span></span><span class="pull-right"><a class="user-label" href="javascript:;" data-id="<?php echo $data->create_user; ?>"><img style="height: 24px;width: 24px;vertical-align:top;border-radius: 2px;" height="24" width="24" src="<?php echo $this->createUrl("getimage", array("id" => $data->create_user, "type" => "avatar")); ?>"></a></span></p>
            <p><?php echo (strlen(strip_tags($data->content)) > 300) ? mb_strcut(strip_tags($data->content), 0, 300, 'utf-8') . "..." : $data->content; ?></p>
            <?php $commentCount = Comment::model()->count("pk_id=:pk_id and model=:model and parent_id=0", array(":model" => "answer", ":pk_id" => $data->id)); ?>
            <?php $voteCount = Vote::model()->count("pk_id=:pk_id and opinion=0 and model='answer'",array(":pk_id"=> $data->id));?>
            <div class="clearfix" style="font-size: 12px;padding-top: 5px;color:#ccc;"><a title="<?php echo $voteCount."个赞同";?>" href="<?php echo $this->createUrl("answer", array("id" => $data->id)); ?>" style="margin-right: 10px;"><i class="icon-thumbs-up" style="margin-right: 4px;"></i><?php echo $voteCount; ?> 赞同</a> <a href="<?php echo $this->createUrl("answer", array("id" => $data->id)); ?>" title="<?php echo $commentCount; ?>条评论"><i class="icon-bubble-dots-4" style="margin-right: 4px;"></i><?php echo $commentCount; ?> 评论</a>
           <?php if ($data->create_user == Yii::app()->user->id || Yii::app()->user->name == "admin") { ?>
                <div title="设置" class="more btn-group btn-mini inline" style="padding:0;vertical-align:top;margin-right: 4px;"><a data-toggle="dropdown" class="btn btn-link dropdown-toggle" style="padding:0 0  0 10px;font-size: 12px;bottom:1px;"><i class="icon-cog"></i> 设置</a>
                                <ul class="dropdown-menu">
    <?php if ($data->create_user == Yii::app()->user->id) { ?>
                                        <li><a  onclick="changeComment(<?php echo $data->id; ?>, 1)"  href="javascript:;"><i class="<?php echo $data->anonymity_yn == 1 ? 'icon-checkmark' : ''; ?>"></i> 允许任何人评论</a></li>
                                        <li><a onclick="changeComment(<?php echo $data->id; ?>, 2)" href="javascript:;"><i class="<?php echo $data->anonymity_yn == 2 ? 'icon-checkmark' : ''; ?>"></i> 允许我关注的人评论</a></li>
                                        <li><a onclick="changeComment(<?php echo $data->id; ?>, 3)" href="javascript:;"><i class="<?php echo $data->anonymity_yn == 3 ? 'icon-checkmark' : ''; ?>"></i> 禁止评论</a></li>
                                        <li class="divider"></li>
    <?php } ?>
                                    <li><a data-action="deleteanswer" data-answerid="<?php echo $data->id;?>"  href="javascript:;"><i class="icon-remove"></i> 删除</a></li>
                                </ul>
                            </div>
<?php } ?>
                <a  style="margin-left: 10px;" href="javascript:;" onclick="report(<?php echo $data->id; ?>, 'answer')" title="举报"><i class="icon-flag" style="margin-right: 4px;"></i> 举报</a>
                <span class="pull-right">
                    <?php
                    if ($commentCount > 0) {
                        $commentModel = Comment::model()->find("pk_id=:pk_id and model=:model and parent_id=0 order by create_time desc", array(":model" => "answer", ":pk_id" => $data->id));
                        ?>
                        <a class="user-label" href="javascript:;" data-id="<?php echo $commentModel->user_id; ?>"><?php echo User::getNameById($commentModel->user_id); ?></a>
                        <?php echo Comment::timeintval($commentModel->create_time) . "评论"; ?>
                    <?php
                    } else {
                        echo "暂无评论";
                    }
                    ?>
                </span>
            </div>
        </div>
    </div>
</div>