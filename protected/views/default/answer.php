<style>
    .content{margin: 0 auto; width: 1030px; }
    .content .content-left{width:720px;position: relative; display: inline-block;} 
    .content .sidebar{width:260px; border-width: 2px; border-style: solid;border-top: 0;background-color: #fff; display: inline-block;height: 260px;background-color: #FAFAFA;}
    .content .pic{display: inline-block; position: absolute; left: 210px; top:0;padding: 10px;}
    .content .content-right{display: inline-block; width: 260px; float: right;}
    .content .content-right .grid-view{padding-top: 0;}
    .content .all-category{ width: 900px;padding-top: 20px;}
    .content .all-category .title{height:30px;line-height: 30px;border-bottom:1px solid #ccc;width:760px;}
    .content .all-category .title .more{padding-left:20px;display: inline-block;float: right;}
    .hot-category-list{  border-bottom: 1px dotted #ccc;padding-top: 10px; padding-bottom: 10px;}
    .hot-category-list .label{height: 20px; line-height: 20px;}
    .hot-category-list .l-title{ float: left;width: 60px;text-align: right;}
    .hot-category-list .l-right{ float: left; width: 1030px;}
    .hot-category-list .l-right li{display: inline-block;}
    .hot-category-list  a{color:#fff;}
    .hot-category-list  .close-label{color:#fff;}
    .hot-category-list  .close-label  a{color:#F9F9F9; display:inline-block;padding-left: 4px;}
    .text{
        padding:20px 0;
        text-align: center;
        height: 24px;
        font-family: '微软雅黑 Bold', '微软雅黑';
        font-weight: 700;
        font-style: normal;
        font-size: 18px;
        color: #DA552F;
    }
    .list-hover{
        background-color: #FCF5E2;
        cursor: pointer;
    }

    .login{
        position: absolute;
        right:0;
        top:100px;
        z-index: 4;
    }
    .main-content-top  .upvote{
        position: absolute;
        left: -40px;
    }

    .vote-number .btn-mini{
        width: 30px;
        padding: 0;
    }
    .vote-list{margin-bottom: 20px;padding-bottom: 20px;border-bottom: 1px solid #ddd;}
    .vote-list .accepted-flag{color:#fff;text-align: center;position: relative;left: -9px;height: 60px;display: inline-block;text-decoration: none !important;line-height: 60px;}
    .vote-list .accepted-flag i{font-size: 50px;padding: 0;margin: 0;line-height: 60px;}
    .vote-list .accepted-flag span{position: absolute;top:0;left: 0;display: inline-block;width: 100%;line-height: 50px;}
    .vote-list .accepted-checked i{color:#075fb6 !important;}
    .vote-list .accepted-selectecd i{color:#000 !important;}
</style>
<?php
$questModel = Question::model()->findByPk($model->question_id);
$hideArray = $questModel->hide_answer_id == "" ? array() : explode(",", trim($questModel->hide_answer_id, ","));
$type = (!in_array($model->id, $hideArray)) ? 0 : 1;
$createUser = $model->anonymity_yn == 1 ? User::getNameById($model->create_user) : "匿名用户";
$this->pageTitle = $questModel->title . " - " . $createUser . "的回答" . " - " . Yii::app()->name;
$this->widget('bootstrap.widgets.TbBreadcrumbs', array('links' => array("问题" => array("allquestion"), $questModel->title => array("question", "id" => $model->question_id), $createUser . "的回答")));
?>
<div class="content clearfix">
    <div class="content-left">
        <div class="question-body clearfix">
            <div class="pull-left vote-number clearfix">
                <div class="pull-left opt-list" style="width: 50px;"> <a  rel="tooltip" data-original-title="<?php echo Vote::model()->count("opinion=0 and model=:model and pk_id=:pk_id and create_user=:create_user", array(":create_user" => Yii::app()->user->id, ":pk_id" => $model->id, ":model" => "answer")) > 0 ? '取消赞同' : '赞同'; ?>"  class="btn btn-mini <?php echo Vote::model()->count("opinion=0  and pk_id=:pk_id and create_user=:create_user and model=:model", array(":model" => "answer", ":create_user" => Yii::app()->user->id, ":pk_id" => $model->id)) > 0 ? 'active' : ''; ?>" name="<?php echo (Yii::app()->user->isGuest) ? 'noLogin' : 'agreeeAnswer'; ?>" data-model="answer" data-pk="<?php echo $model->id; ?>"><i class="icon icon-arrow-up"></i><br/><span class="count"><?php echo Vote::model()->count("opinion=0 and model=:model and pk_id=:pk_id", array(":model" => "answer", ":pk_id" => $model->id)); ?></span></a> <br/><a class="btn btn-mini <?php echo Vote::model()->count("opinion=1 and model=:model and pk_id=:pk_id and create_user=:create_user", array(":model" => "answer", ":create_user" => Yii::app()->user->id, ":pk_id" => $model->id)) > 0 ? 'active' : ''; ?>" rel="tooltip" data-original-title="<?php echo Vote::model()->count("opinion=1 and pk_id=:pk_id and model=:model and create_user=:create_user", array(":create_user" => Yii::app()->user->id, ":pk_id" => $model->id, ":model" => "answer")) > 0 ? '取消反对' : '反对，不会显示你的姓名'; ?>"  data-placement="bottom" name="<?php echo (Yii::app()->user->isGuest) ? 'noLogin' : 'disagreeeAnswer'; ?>"   data-model="answer" data-pk="<?php echo $model->id; ?>"><i style="font-size:14px;" class="icon icon-arrow-down"></i></a></div>
            </div>
            <div class="question-content" style="margin-left:50px;">
                <p class="clearfix" style="font-size: 12px;height: 24px;line-height: 24px;"><span class="pull-left"><?php if ($model->is_anonymous == 1) { ?>匿名用户<?php } else { ?><a href='<?php echo $this->createUrl("userinfo", array("user_id" => $model->create_user)); ?>' class="user-label" data-id="<?php echo $model->create_user; ?>"><?php echo User::getNameById($model->create_user); ?></a><?php } ?><span style="color:#ccc;margin-left: 10px;"><?php echo Comment::timeintval($model->create_time); ?> 回答</span></span><span class="pull-right"><?php if ($model->is_anonymous == 1) { ?><?php echo ($model->create_user == Yii::app()->user->id) ? '<a name="Notanonymous" href="javascript:;" data-answerid="' . $model->id . '"><i class="icon-pushpin"></i>取消匿名</a>' : ''; ?><?php } else { ?><a class="user-label" href="javascript:;" data-id="<?php echo $model->create_user; ?>"><img style="height: 24px;width: 24px;vertical-align:top;border-radius: 2px;" height="24" width="24" src="<?php echo $this->createUrl("getimage", array("id" => $model->create_user, "type" => "avatar")); ?>"></a><?php } ?></span></p>
                <div class="vote-info">
                    <?php $voteOpinion = Vote::model()->findAll("pk_id = :pk_id and model=:model and opinion=0 order by create_time desc", array(":pk_id" => $model->id, ":model" => "answer")); ?>
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
                <?php if ($model->content != "") { ?>
                    <?php
                    if (($type == 1) && ($model->hide_reason != "")) {
                        $andminModel = User::model()->find("user_name='admin'");
                        ?>
                        <div class='well' style='margin-top: 5px;'><?php echo "此回答已经被屏蔽，屏蔽原因是：" . $model->hide_reason; ?>，若有问题请联系<a href='javascript:;' name='<?php echo (Yii::app()->user->isGuest) ? "noLogin" : "reply"; ?>' data-uid='<?php echo $andminModel->id; ?>'  data-username='admin'>管理员</a></div>
                    <?php } else { ?>
                        <p><?php echo $data->content; ?></p>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>
        <?php $followees = User::model()->findByPk($model->create_user)->followees; ?>
        <?php $allow_comment = (($model->anonymity_yn == 1) || (($model->anonymity_yn == 2) && ($followees != "") && in_array(Yii::app()->user->id, explode(",", trim($followees, ","))))) ? true : false; ?>
        <?php $commentCount = Comment::model()->count("pk_id=:pk_id and model='answer' and parent_id=0", array(":pk_id" => $_GET['id'])); ?>
        <?php if ($allow_comment || $commentCount > 0) { ?>
            <fieldset class="clearfix">
                <legend style="margin-bottom:10px;font-size:20px;"><strong><?php echo $commentCount; ?>条评论</strong></legend>
            </fieldset>
        <?php } ?>
        <?php if ($allow_comment) { ?>
            <div class="list">
                <div class="list-comment">
                    <div class="comment-list clearfix">
                        <div class="item clearfix" style="margin-bottom: 10px;position: relative;"> 
                            <div class="user" style="width:6%;float: left;">      
                                <div class="u-icon"> 
                                    <?php if (Yii::app()->user->isGuest) { ?>
                                        <img height="50" width="50" class="gray" src="<?php echo $this->createUrl("getimage", array("id" => Yii::app()->user->id, "type" => "avatar")); ?>">
                                    <?php } else { ?>
                                        <a   target="_blank" href="<?php echo $this->createUrl('default/userinfo', array('user_id' => Yii::app()->user->id)); ?>"> <img height="50" width="50"  src="<?php echo $this->createUrl("getimage", array("id" => Yii::app()->user->id, "type" => "avatar")); ?>"> </a>  
                                    <? } ?>
                                </div>      
                            </div>  
                            <div class="clearfix box-section" style="width:92%;float: right;">
                                <div class="clearfix  right" style="display:block;left:0;clear:both;right:0;position: relative">
                                    <?php
                                    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
                                        'id' => 'comment-form',
                                        'type' => 'horizontal',
                                        'action' => $this->createUrl('createcomment', array("id" => $_GET["id"], "#" => "form")),
                                    ));
                                    $modelComment = new Comment();
                                    ?>
                                    <?php echo $form->hiddenField($modelComment, 'pk_id', array("value" => $model->id)); ?>
                                    <?php echo $form->hiddenField($modelComment, 'model', array("value" => "answer")); ?>
                                    <?php echo $form->textArea($modelComment, 'content', array('disabled' => Yii::app()->user->isGuest ? true : false, "style" => "width:97%;height:80px;resize: none;padding:10px;float:right;", "placeholder" => Yii::app()->user->isGuest ? "发表评论请先登陆" : "输入评论")); ?>
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
        <?php if ($allow_comment || $commentCount > 0) { ?>
            <?php
            $criteria = new CDbCriteria;
            $criteria->addCondition("pk_id='" . $model->id . "' and model='answer' and parent_id=0");
            $dataProvider = new CActiveDataProvider('Comment', array(
                'criteria' => $criteria,
                'sort' => array(
                    'defaultOrder' => 'create_time asc'
                ),
                'pagination' => array(
                    'pageVar' => 'page',
                    'pageSize' => 5)
                    )
            );
            $this->widget('bootstrap.widgets.TbListView', array(
                'dataProvider' => $dataProvider,
                'itemView' => '../_commentlist',
                'template' => '{items}{pager}',
                'htmlOptions' => array('style' => 'padding-top:0px')
            ));
            ?>
        <?php } ?>
    </div>
    <div class="content-right">
        <div id="setting-tabs" class="tabs-above">
            <?php if (Yii::app()->user->isGuest) { ?>
                <div class="alert alert-info"><?php echo Sys::model()->find()->site_desc; ?></div>
            <?php } ?>
            <fieldset>
                <legend style="margin-bottom: 10px;">其他回答</legend> 
            </fieldset>
            <?php
            $criteria = new CDbCriteria;
            $criteria->order = "create_time desc";
            $criteria->addCondition("id!=" . $model->id);
            $criteria->addCondition("question_id=" . $model->question_id);
            $answerModel = Answer::model()->findAll($criteria);
            $count = array();
            if (!empty($answerModel)) {
                echo '<table><tbody>';
                foreach ($answerModel as $answer) {
                    $j++;
                    if ($j == 11) {
                        break;
                    }
                    ?>
                    <tr><td style="padding-left:0px;"><a  href="<?php echo $this->createUrl("answer", array("id" => $answer['id'])); ?>"><?php echo (strlen(strip_tags($answer['content'])) > 70) ? mb_strcut(strip_tags($answer['content']), 0, 70, 'utf-8') . "..." : $answer['content']; ?></a><span style="color:#ccc;margin-left:0;">(共<?php echo Comment::model()->count("pk_id = :pk_id and parent_id=0 and model='answer'", array(":pk_id" => $answer['id'])); ?>条评论,<?php echo (Question::model()->count("answer_id=" . $answer['id']) > 0) ? "已采纳" : "未采纳"; ?>)</span></td></tr>
                    <?php
                }
                echo '</tbody></table>';
            } else {
                echo "<div class='alert alert-info'>暂无其他回答</div>";
            }
            ?>
            <fieldset>
                <legend style="margin-bottom: 10px;">回答状态</legend>
            </fieldset>
            <p style="color: #ccc">
                <?php $commentModel = Comment::model()->find("pk_id=:pk_id and model='answer' order by create_time desc", array(":pk_id" => $model->id)); ?>
                <span style="display: block;">最近活动于<?php echo ($commentModel == NULL) ? Comment::timeintval($model->create_time) : Comment::timeintval($commentModel->create_time); ?>,<?php echo (Question::model()->count("answer_id=" . $model->id) > 0) ? "已采纳" : "未采纳"; ?></span>
            </p>
        </div>
    </div>
</div>
<script>
    var agreeVoteUrl = "<?php echo $this->createUrl("agreeavote"); ?>";
    var disagreeVoteUrl = "<?php echo $this->createUrl("disagreevote"); ?>";
    var acceptedUrl = "<?php echo $this->createUrl("acceptedanswer"); ?>";
    var createReplyUrl = '<?php echo $this->createUrl("createreply"); ?>';
    var deleteCommentUrl = '<?php echo $this->createUrl("deleteComment"); ?>';
    var deleteReplyUrl = '<?php echo $this->createUrl("deleteReply"); ?>';
    $(document).ready(function() {
        $(document).delegate(".all", 'click', function() {
            $(this).parent(".vote-info").find(".vote-all").show();
            $(this).parent(".vote-info").find(".vote-some").hide();
            $(this).text("赞同");
        });
        $(document).delegate("a[data-name='agreeeAnswer']", 'click', function() {
            var self = $(this);
            var pk_id = self.attr("data-pk");
            var model = self.attr("data-model");
            $.post(agreeVoteUrl, {'pk_id': pk_id, 'model': model}, function(data) {
                if (data.message == "ok") {
                    self.toggleClass("active");
                    self.parent().find(".icon-arrow-down").parent().removeClass("active");
                    self.find(".count").text(data.count);
                    if (self.attr("data-original-title") == "赞同") {
                        self.attr("data-original-title", "取消赞同");
                        self.parent().find(".icon-arrow-down").parent().attr("data-original-title", "反对");
                        var someVotes = self.parents(".vote-number").next().find(".vote-info");
                        var aHtml = "<a href='javascript:;' class='user-label' data-value='<?php echo Yii::app()->user->id ?>' data-id='<?php echo Yii::app()->user->id ?>'><?php echo User::getNameById(Yii::app()->user->id); ?></a>";
                        if (someVotes.find("a[data-value='" +<?php echo Yii::app()->user->id; ?> + "']").length == 0) {
                            if (someVotes.find("a").length == 0) {
                                someVotes.text('');
                                someVotes.append(aHtml);
                                someVotes.append(" <a href='javascript:;' class='some'>赞同</a>");
                            } else {
                                someVotes.find('.vote-some').append(aHtml + "<span>、</span>");
                                someVotes.find('.vote-some').find("a[data-value='" +<?php echo Yii::app()->user->id; ?> + "']").next().insertBefore(someVotes.find('.vote-some').find("a:eq(0)"));
                                someVotes.find('.vote-some').find("a[data-value='" +<?php echo Yii::app()->user->id; ?> + "']").insertBefore(someVotes.find('.vote-some').find("a:eq(0)").prev());
                                someVotes.find('.vote-all').append(aHtml + "<span>、</span>");
                                someVotes.find('.vote-all').find("a[data-value='" +<?php echo Yii::app()->user->id; ?> + "']").next().insertBefore(someVotes.find('.vote-all').find("a:eq(0)"));
                                someVotes.find('.vote-all').find("a[data-value='" +<?php echo Yii::app()->user->id; ?> + "']").insertBefore(someVotes.find('.vote-all').find("a:eq(0)").prev());
                            }
                        }
                    } else {
                        self.attr("data-original-title", "赞同");
                        var someVotes = self.parents(".vote-number").next().find(".vote-info");
                        var userId = someVotes.find('.vote-some').length > 0 ? someVotes.find('.vote-some').find("a[data-value='" +<?php echo Yii::app()->user->id; ?> + "']") : someVotes.find("a[data-value='" +<?php echo Yii::app()->user->id; ?> + "']");
                        if (userId.next().html() == "、") {
                            userId.next().remove();
                        }
                        userId.remove();
                        if (someVotes.find('.vote-all').length > 0) {
                            var allUserId = someVotes.find('.vote-all').find("a[data-value='" +<?php echo Yii::app()->user->id; ?> + "']");
                            if (allUserId.next().html() == "、") {
                                allUserId.next().remove();
                            }
                            allUserId.remove();
                        }
                        if (data.count == 0) {
                            someVotes.empty();
                        }
                    }

                } else {
                    alert('操作失败');
                }
            }, 'json');
        });
        $(document).delegate("a[data-name='disagreeeAnswer']", 'click', function() {
            var self = $(this);
            var pk_id = self.attr("data-pk");
            var model = self.attr("data-model");
            $.post(disagreeVoteUrl, {'pk_id': pk_id, 'model': model}, function(data) {
                if (data.message == "ok") {
                    self.toggleClass("active");
                    self.parent().find(".icon-arrow-up").parent().removeClass("active");
                    self.parent().find(".icon-arrow-up").parent().find(".count").text(data.count);
                    if (self.attr("data-original-title") == "取消反对") {
                        self.attr("data-original-title", "反对，不会显示你的姓名");
                    } else {
                        self.attr("data-original-title", "取消反对");
                        self.parent().find(".icon-arrow-up").parent().attr("data-original-title", "赞同");
                        var someVotes = self.parents(".vote-number").next().find(".vote-info");
                        var userId = someVotes.find('.vote-some').length > 0 ? someVotes.find('.vote-some').find("a[data-value='" +<?php echo Yii::app()->user->id; ?> + "']") : someVotes.find("a[data-value='" +<?php echo Yii::app()->user->id; ?> + "']");
                        if (userId.next().html() == "、") {
                            userId.next().remove();
                        }
                        userId.remove();
                        if (someVotes.find('.vote-all').length > 0) {
                            var allUserId = someVotes.find('.vote-all').find("a[data-value='" +<?php echo Yii::app()->user->id; ?> + "']");
                            if (allUserId.next().html() == "、") {
                                allUserId.next().remove();
                            }
                            allUserId.remove();
                        }
                        if (data.count == 0) {
                            someVotes.empty();
                        }
                    }
                } else {
                    alert('操作失败');
                }
            }, 'json');
        });
        $(document).delegate(".vote-list", 'mouseenter', function() {
            $(this).find("a[data-name='accepted']").show();
        });
        $(document).delegate(".vote-list", 'mouseleave', function() {
            $(this).find("a[data-name='accepted']").hide();
        });
        $(document).delegate("a[data-name='accepted']", 'click', function() {
            var self = $(this);
            var answer_id = self.attr("data-id");
            $.post(acceptedUrl, {'answer_id': answer_id}, function(data) {
                if (data.message == "ok") {
                    $.fn.yiiListView.update("ajaxAnswerList");
                } else {
                    alert('操作失败');
                }
            }, 'json');
        });
        $(document).delegate("#submitcomment", 'click', function() {
            if ($("#Comment_content").val() == "") {
                alert("请输入相应的评论");
            } else {
                $("#comment-form").submit();
            }
        });
        $(document).delegate(".comment-reply", 'mouseenter', function() {
            $(this).find("a[data-name='reply-comment']").show();
            $(this).find("a[data-name='delete-reply']").show();
        });
        $(document).delegate(".comment-reply", 'mouseleave', function() {
            $(this).find("a[data-name='reply-comment']").hide();
            $(this).find("a[data-name='delete-reply']").hide();
        });
        $(document).delegate("a[data-name='reply-comment']", 'click', function() {
            var userId = $(this).attr("user-value");
            var commentId = $(this).attr("data-value");
            var userName = $(this).attr("name-value");
            var page = $(this).attr("data-page");
            if ($("#createDiaryReply").length > 0) {
                $("#createDiaryReply").remove();
            }
            ;
            form = $("<form id='createDiaryReply' style='position:relative;right:0;padding:10px;' class='form well'></form>");
            form.attr("action", createReplyUrl);
            form.attr("method", "POST");
            user = $("<div style='font-size:14px;'><b>@ " + userName + "</b> :</div>");
            form.append(user);
            userIuput = $("<input type='hidden' name='user_id'/>");
            userIuput.attr("value", userId);
            form.append(userIuput);
            commentInput = $("<input type='hidden' name='comment_id'/>");
            commentInput.attr("value", commentId);
            form.append(commentInput);
            content = $("<textarea name='content' class='content' style='width:78%;margin-bottom:0;'></textarea>");
            form.append(content);
            pageInput = $("<input type='hidden' name='page'/>");
            pageInput.attr("value", page);
            form.append(pageInput);
            submit = $('<button class="btn" type="button" id="create_diary_reply" style="margin-left:10px;">发表</button>');
            form.append(submit);
            form.insertAfter($(this).parent());
            return false;
        });
        $(document).delegate("#create_diary_reply", 'click', function() {
            if ($(this).parents("#createDiaryReply").find(".content").val() == "") {
                alert("请输入回复内容");
            } else {
                $("#createDiaryReply").submit();
            }
        });
        $(document).delegate("a[data-name='delete-comment']", 'click', function() {
            if (window.confirm("删除评论时，此评论下的回复也会全部删除，确定要删除所选的评论吗?")) {
                var id = $(this).attr("data-value");
                var self = $(this);
                $.ajax({
                    url: deleteCommentUrl,
                    data: {'id': id},
                    type: "POST",
                    dataType: "html",
                    success: function(data) {
                        if (data == "ok") {
                            self.parents(".list").remove();
                        } else {
                            alert("删除失败");
                        }
                    }
                });
                return false;
            } else {
                return false;
            }
        });
        $(document).delegate("a[data-name='delete-reply']", 'click', function() {
            if (window.confirm("确定要删除所选的回复吗?")) {
                var id = $(this).attr("data-value");
                var self = $(this);
                $.ajax({
                    url: deleteReplyUrl,
                    data: {'id': id},
                    type: "POST",
                    dataType: "html",
                    success: function(data) {
                        if (data == "ok") {
                            self.parents(".comment-reply").remove();
                        } else {
                            alert("删除失败");
                        }
                    }

                });
                return false;
            } else {
                return false;
            }
        });
    });
</script>