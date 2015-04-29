<div class="vote-list">
    <div class="product-comment">
        <div class="comment-list clearfix">
            <div class="item clearfix" style="position: relative;padding-bottom: 5px;">
                <a name="answer_<?php echo $data->id; ?>"></a>
                <div class="pull-left vote-number clearfix" style="padding-top: 5px;">
                    <div class="pull-left opt-list" style="width: 50px;"> <a  rel="tooltip" data-original-title="<?php echo Vote::model()->count("opinion=0 and model=:model and pk_id=:pk_id and create_user=:create_user", array(":create_user" => Yii::app()->user->id, ":pk_id" => $data->id, ":model" => "answer")) > 0 ? '取消赞同' : '赞同'; ?>"  class="btn btn-mini <?php echo Vote::model()->count("opinion=0  and pk_id=:pk_id and create_user=:create_user and model=:model", array(":model" => "answer", ":create_user" => Yii::app()->user->id, ":pk_id" => $data->id)) > 0 ? 'active' : ''; ?>" name="<?php echo (Yii::app()->user->isGuest) ? 'noLogin' : 'agreeeAnswer'; ?>" data-model="answer" data-pk="<?php echo $data->id; ?>"><i class="icon icon-arrow-up"></i><br/><span class="count"><?php echo Vote::model()->count("opinion=0 and model=:model and pk_id=:pk_id", array(":model" => "answer", ":pk_id" => $data->id)); ?></span></a> <br/><a class="btn btn-mini <?php echo Vote::model()->count("opinion=1 and model=:model and pk_id=:pk_id and create_user=:create_user", array(":model" => "answer", ":create_user" => Yii::app()->user->id, ":pk_id" => $data->id)) > 0 ? 'active' : ''; ?>" rel="tooltip" data-original-title="<?php echo Vote::model()->count("opinion=1 and pk_id=:pk_id and create_user=:create_user", array(":create_user" => Yii::app()->user->id, ":pk_id" => $data->id)) > 0 ? '取消反对' : '反对，不会显示你的姓名'; ?>"  data-placement="bottom" name="<?php echo (Yii::app()->user->isGuest) ? 'noLogin' : 'disagreeeAnswer'; ?>"  data-model="answer" data-pk="<?php echo $data->id; ?>"><i style="font-size:14px;" class="icon icon-arrow-down"></i></a><br>
                        <?php $count = Question::model()->count("answer_id=" . $data->id); ?>
                        <?php if ($count > 0) { ?>
                            <a class="accepted-flag accepted-checked"><i class="icon-bookmark"></i><span>采纳</span></a>
                        <?php } else if ((Question::model()->findByPk($data->question_id)->answer_id == 0) && (Yii::app()->user->id == Question::model()->findByPk($data->question_id)->create_user)) { ?>
                            <a class="accepted-flag accepted-selectecd"   data-placement="bottom" rel="tooltip" data-original-title="采纳此回答" href="javascript:;" name="accepted" data-id="<?php echo $data->id; ?>" style="display:none;"><i class="icon-bookmark"></i><span>采纳</span></a>
                        <?php } ?>

                    </div>
                </div>
                <?php 
                    $questModel = Question::model()->findByPk($data->question_id);
                                             $hideArray = $questModel->hide_answer_id==""? array() : explode(",", trim($questModel->hide_answer_id,","));
                                             $type = (!in_array($data->id,$hideArray)) ? 0 : 1;
                                             ?>
                <div class="answer-body" style="margin-left: 50px;">
                    <p class="clearfix" style="font-size: 12px;height: 24px;line-height: 24px;"><span class="pull-left"><?php if ($data->is_anonymous == 1) { ?>匿名用户<?php } else { ?><a href='<?php echo $this->createUrl("userinfo", array("user_id" => $data->create_user)); ?>' class="user-label" data-id="<?php echo $data->create_user; ?>"><?php echo User::getNameById($data->create_user); ?></a><?php } ?><span style="color:#ccc;margin-left: 10px;"><?php echo Comment::timeintval($data->create_time); ?> 回答</span></span><span class="pull-right"><?php if ($data->is_anonymous == 1) { ?><?php echo ($data->create_user == Yii::app()->user->id) ? '<a name="Notanonymous" href="javascript:;" data-answerid="' . $data->id . '"><i class="icon-pushpin"></i>取消匿名</a>' : ''; ?><?php } else { ?><a class="user-label" href="javascript:;" data-id="<?php echo $data->create_user; ?>"><img style="height: 24px;width: 24px;vertical-align:top;border-radius: 2px;" height="24" width="24" src="<?php echo $this->createUrl("getimage", array("id" => $data->create_user, "type" => "avatar")); ?>"></a><?php } ?></span></p>
                    <div class="vote-info">
                        <?php $voteOpinion = Vote::model()->findAll("pk_id = :pk_id and model=:model and opinion=0 order by create_time desc", array(":pk_id" => $data->id, ":model" => "answer")); ?>
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
                    <?php $answerID = Question::model()->findByPk($data->question_id)->answer_id;?>
                    <?php if (($data->create_user == Yii::app()->user->id && isset($_GET["answer_id"]) && ($answerID!=$data->id))) { ?>
                        <div class="clearfix  right" style="display:block;left:0;clear:both;right:0;position: relative">
                            <?php
                            $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
                                'id' => 'answer-form',
                                'action' => $this->createUrl('updateanswer', array("id" =>$data->id, "#" => "form")),
                            ));
                            $modelAnswer = Answer::model()->findByPk($data->id);
                            ?>
                            <?php echo $form->ckeditorRow($modelAnswer, 'content', array("style" => "width:97%;height:80px;resize: none;padding:10px;float:right;", "labelOptions" => array("label" => false))); ?>
                            <div class="pull-left" style="padding-top: 10px;">不知道答案？你还可以 
                                <?php
                                $this->widget('bootstrap.widgets.TbButton', array(
                                    'buttonType' => 'button',
                                    'label' => '邀请回答',
                                    'size' => 'mini',
                                    'disabled' => Yii::app()->user->isGuest ? true : false,
                                    'htmlOptions' => array('onclick' => "$('#helpModal').modal('show');"),
                                    'type' => 'default',
                                ));
                                ?>
                            </div>
                            <div class="pull-left" style="margin-left:300px;padding-top: 10px;">
                                <?php
                                  $this->widget('bootstrap.widgets.TbButton', array(
                                'buttonType' => 'link',
                                'label' => '取消',
                                'type'=>'link',
                                'htmlOptions' => array('class' => 'pull-right'),
                                 'url'=>$this->createUrl("question",array("id"=>$data->question_id))
                            ));
                                echo $form->checkBoxListInlineRow($modelAnswer, 'is_anonymous', array('1' => '匿名'), array('class' => 'pull-right', 'labelOptions' => array("label" => false), 'disabled' => Yii::app()->user->isGuest ? true : false));
                                ?>
                            </div>
                            <?php
                            $this->widget('bootstrap.widgets.TbButton', array(
                                'buttonType' => 'button',
                                'label' => '提交',
                                'disabled' => Yii::app()->user->isGuest ? true : false,
                                'htmlOptions' => array('id' => 'submitcomment', 'class' => 'pull-right', 'style' => 'margin-top:10px;'),
                                'type' => Yii::app()->user->isGuest ? 'default' : 'info',
                            ));
                            ?>
                            <?php $this->endWidget(); ?>
                        </div>
                        <?php } else if(($type==1) && ($data->hide_reason!="")){ 
                            $andminModel = User::model()->find("user_name='admin'");
                            ?>
                    <div class='well' style='margin-top: 5px;'><?php echo "此回答已经被屏蔽，屏蔽原因是：".$data->hide_reason;?>，若有问题请联系<a href='javascript:;' name='<?php echo (Yii::app()->user->isGuest) ? "noLogin" : "reply"; ?>' data-uid='<?php echo $andminModel->id;?>'  data-username='admin'>管理员</a></div>
                        <?php }else{?>
                        <?php echo $data->content; ?>
                    <?php } ?>
                    <?php $commentCount = Comment::model()->count("pk_id=:pk_id and model=:model and parent_id=0", array(":model" => "answer", ":pk_id" => $data->id)); ?>
                    <div class="answer-opt clearfix" style="font-size: 12px;padding-top: 5px;color:#ccc;"><a href="<?php echo $this->createUrl("answer", array("id" => $data->id, "#" => "answer-comment")); ?>" title="<?php echo $commentCount; ?>条评论"><i class="icon-bubble-dots-4" style="margin-right: 4px;"></i><?php echo $commentCount; ?>条评论</a>
                    <?php if ($data->create_user == Yii::app()->user->id || Yii::app()->user->name == "admin") { ?>
                            <div title="设置" class="more btn-group btn-mini inline" style="padding:0;vertical-align:top;margin-right: 4px;"><a data-toggle="dropdown" class="btn btn-link dropdown-toggle" style="padding:0 0  0 10px;font-size: 12px;bottom:1px;"><i class="icon-cog"></i> 设置</a>
                                <ul class="dropdown-menu">
    <?php if ($data->create_user == Yii::app()->user->id) { ?>
                                        <li><a  onclick="changeComment(<?php echo $data->id; ?>, 1)"  href="javascript:;"><i class="<?php echo $data->anonymity_yn == 1 ? 'icon-checkmark' : ''; ?>"></i> 允许任何人评论</a></li>
                                        <li><a onclick="changeComment(<?php echo $data->id; ?>, 2)" href="javascript:;"><i class="<?php echo $data->anonymity_yn == 2 ? 'icon-checkmark' : ''; ?>"></i> 允许我关注的人评论</a></li>
                                        <li><a onclick="changeComment(<?php echo $data->id; ?>, 3)" href="javascript:;"><i class="<?php echo $data->anonymity_yn == 3 ? 'icon-checkmark' : ''; ?>"></i> 禁止评论</a></li>
                                        <li class="divider"></li>
    <?php } ?>
                                        <?php if(Yii::app()->user->name == "admin") { 
                                            ?><li><a onclick="collapsedAnswer(<?php echo $data->id; ?>,<?php echo $type; ?>)"  href="javascript:;"><i class="icon-cog-2"></i> <?php echo $type==0 ? "折叠":"取消折叠";?> </a></li><?php }?>
                                    <li><a onclick="deleteAnswer(<?php echo $data->id; ?>)"  href="javascript:;"><i class="icon-remove"></i> 删除</a></li>
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
    </div>
</div>
