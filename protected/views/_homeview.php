<style>
    .title-link{ display: block;
                 font-size: 14px;
                 font-weight: 700;
                 text-align: left;
                 padding-left: 0;
                 margin-left: 0;
    }
</style>
<div class="profile-section-item" style='padding: 4px;'>
    <span style='color:#999;width:400px;'> 
        <?php if ($data->from_id != Yii::app()->user->id) { ?><img style='margin-right:4px;' height="20" width="20" src="<?php echo $this->createUrl("getimage", array("id" => $data->from_id, "type" => "avatar")); ?>"  alt="<?php echo User::getNameById($data->from_id); ?>"><?php } ?><a  style='color:#999' data-id="<?php echo $data->from_id; ?>"  class="user-label" href="<?php echo ($data->from_id != Yii::app()->user->id) ? $this->createUrl('default/userinfo', array('user_id' => $data->from_id)) : "javascript::"; ?>">
            <?php echo ($data->from_id == Yii::app()->user->id) ? "你" : User::getNameById($data->from_id); ?></a>
        <?php if ($data->notification_type == "report") { ?>举报了<a  title="<?php echo $data->pk_id; ?>"  class="user-label" target="_blank" href="<?php echo $this->createUrl('default/userinfo', array('user_id' => $data->pk_id)); ?>"> <img style='margin-right:4px;' height="20" width="20" src="<?php echo $this->createUrl("getimage", array("id" => $data->pk_id, "type" => "avatar")); ?>"  alt="<?php echo User::getNameById($data->pk_id); ?>"><?php echo User::getNameById($data->pk_id); ?> </a>  
            <?php echo $data->content; ?>
        <?php
        } else if (in_array($data->notification_type, array("reportanswer", "reportquestion", "reportarticle"))) {
            if ($data->notification_type == "reportquestion") {
                $questionModel = Question::model()->findByPK($data->pk_id);
                $user = User::model()->findByPK($questionModel->create_user);
                $message = "举报了 <img style='margin:0 4px;' height='20' width='20' src='" . $this->createUrl("getimage", array("id" => $user->id,"type"=>"avatar")) . "'/><a class='user-label'  data-id='" . $user->id . "' href='javascript:;' title='" . $user->user_name . " - 识趣' target='_blank'>" . $user->user_name . "</a> 的问题";
                $content = "<a href='" . $this->createUrl("question", array("id" => $data->pk_id)) . "' title='" . $questionModel->title . " - 识趣' target='_blank'>" . $questionModel->title . "</a>";
            } else if ($data->notification_type == "reportanswer") {
                $answerModel = Answer::model()->findByPK($data->pk_id);
                $question = Question::model()->findByPK($answerModel->question_id);
                $user = User::model()->findByPK($answerModel->create_user);
                $message = "举报了 <img style='margin:0 4px;' height='20' width='20' src='" . $this->createUrl("getimage", array("id" => $user->id,"type"=>"avatar")) . "'/><a class='user-label'  data-id='" . $user->id . "'' href='javascript:;' title='" . $user->user_name . " - 识趣' target='_blank'>" . $user->user_name . "</a> 的回答";
                $content = "<a href='" . $this->createUrl("answer", array("id" => $data->pk_id)) . "' title='" . $question->title . " - " . $user->user_name . "的回答 - 识趣' target='_blank'>" . $answerModel->content . "</a>";
            } else if ($data->notification_type == "reportarticle") {
                $articleModel = Article::model()->findByPK($data->pk_id);
                $user = User::model()->findByPK($articleModel->create_user);
                $message = "举报了 <img style='margin:0 4px;' height='20' width='20' src='" . $this->createUrl("getimage", array("id" => $user->id,"type"=>"avatar")) . "'/><a class='user-label'  data-id='" . $user->id . "' href='javascript:;' title='" . $user->user_name . " - 识趣' target='_blank'>" . $user->user_name . "</a> 的文章";
                $content = "<a href='" . $this->createUrl("article", array("id" => $data->pk_id)) . "' title='" . $articleModel->subject . " - 识趣' target='_blank'>" . $articleModel->subject . "</a>";
            }
            echo $message." ".$data->content;
        } else {
            ?>

            <?php if (($data->notification_type == "attention") || ($data->notification_type == "block")) { ?>
                <?php echo $data->content; ?>
                <img style='margin-right:4px;' height="20" width="20" src="<?php echo $this->createUrl("getimage", array("id" => $data->pk_id, "type" => "avatar")); ?>"  alt="<?php echo User::getNameById($data->pk_id); ?>"><a  title="查看Ta个人信息" target="_blank" href="<?php echo $this->createUrl('default/userinfo', array('user_id' => $data->pk_id)); ?>"> <?php echo User::getNameById($data->pk_id); ?> </a>  
                <?php
            } else if (($data->notification_type == "article") || ($data->notification_type == "createarticle")) {
                $artcileModel = ($data->notification_type == "createarticle") ? Article::model()->findByPk($data->pk_id) : Article::model()->findByPk(Comment::model()->findByPk($data->pk_id)->pk_id);
                echo (Yii::app()->user->id != $artcileModel->create_user) ? str_replace("你", CHtml::tag("img", array("style" => "margin:0 4px;", "height" => "20", "width" => "20", "src" => $this->createUrl("getimage", array("id" => $artcileModel->create_user, "type" => "avatar")))) . CHtml::link(User::getNameById($artcileModel->create_user), array("default/userinfo", 'user_id' => $artcileModel->create_user), array('data-id' => $artcileModel->create_user, 'class' => 'user-label', 'target' => '_blank')), $data->content) : $data->content;
                $this->widget('bootstrap.widgets.TbButton', array(
                    'type' => 'info',
                    'label' => (strlen($artcileModel->subject) > 40) ? mb_substr($artcileModel->subject, 0, 40, 'utf-8') . '...' : $artcileModel->subject,
                    'type' => 'link',
                    'url' => $this->createUrl("article", array("id" => $artcileModel->id)),
                ));
            } else if (($data->notification_type == "createquestion")) {
                $questionModel = Question::model()->findByPk($data->pk_id);
                echo (Yii::app()->user->id != $questionModel->create_user) ? str_replace("你", CHtml::tag("img", array("style" => "margin:0 4px;", "height" => "20", "width" => "20", "src" => $this->createUrl("getimage", array("id" => $questionModel->create_user, "type" => "avatar")))) . CHtml::link(User::getNameById($questionModel->create_user), array("default/userinfo", 'user_id' => $questionModel->create_user), array('data-id' => $topicModel->create_user, 'class' => 'user-label', 'target' => '_blank')), $data->content) : $data->content;
                $this->widget('bootstrap.widgets.TbButton', array(
                    'type' => 'info',
                    'label' => (strlen($questionModel->title) > 40) ? mb_substr($questionModel->title, 0, 40, 'utf-8') . '...' : $questionModel->title,
                    'type' => 'link',
                    'url' => $this->createUrl("question", array("id" => $questionModel->id)),
                ));
            } else if (($data->notification_type == "answer") || ($data->notification_type == "createanswer")) {
                $answerModel = ($data->notification_type == "createanswer") ? Answer::model()->findByPk($data->pk_id) : Answer::model()->findByPk(Comment::model()->findByPk($data->pk_id)->pk_id);
                $questionModel = Question::model()->findByPk($answerModel->question_id);
                echo (Yii::app()->user->id != $questionModel->create_user) ? str_replace("你", CHtml::tag("img", array("style" => "margin:0 4px;", "height" => "20", "width" => "20", "src" => $this->createUrl("getimage", array("id" => $questionModel->create_user, "type" => "avatar")))) . CHtml::link(User::getNameById($questionModel->create_user), array("default/userinfo", 'user_id' => $questionModel->create_user), array('data-id' => $questionModel->create_user, 'class' => 'user-label', 'style'=>'margin-right:10px;')), $data->content) : $data->content;
                $this->widget('bootstrap.widgets.TbButton', array(
                    'type' => 'info',
                    'label' => (strlen($questionModel->title) > 40) ? mb_substr($questionModel->title, 0, 40, 'utf-8') . '...' : $questionModel->title,
                    'type' => 'link',
                    'url' => $this->createUrl("answer", array("id" => $answerModel->id)),
                ));
            } else if (($data->notification_type == "createask")) {
                $requestModel = Request::model()->findByPk($data->pk_id);
                $questionModel = Question::model()->findByPk($requestModel->question_id);
                echo (Yii::app()->user->id != $requestModel->to_user) ? str_replace("你", CHtml::tag("img", array("style" => "margin:0 4px;", "height" => "20", "width" => "20", "src" => $this->createUrl("getimage", array("id" => $requestModel->to_user, "type" => "avatar")))) . CHtml::link(User::getNameById($requestModel->to_user), array("default/userinfo", 'user_id' => $requestModel->to_user), array('data-id' => $requestModel->to_user, 'class' => 'user-label', 'target' => '_blank')), $data->content) : $data->content;
                $this->widget('bootstrap.widgets.TbButton', array(
                    'type' => 'info',
                    'label' => (strlen($questionModel->title) > 40) ? mb_substr($questionModel->title, 0, 40, 'utf-8') . '...' : $questionModel->title,
                    'type' => 'link',
                    'url' => $this->createUrl("question", array("id" => $questionModel->id)),
                ));
            } else if (($data->notification_type == "question")) {
                $answerModel = Answer::model()->findByPk($data->pk_id);
                $questionModel = Question::model()->findByPk($answerModel->question_id);
                echo (Yii::app()->user->id != $questionModel->create_user) ? str_replace("你", CHtml::tag("img", array("style" => "margin:0 4px;", "height" => "20", "width" => "20", "src" => $this->createUrl("getimage", array("id" => $questionModel->create_user, "type" => "avatar")))) . CHtml::link(User::getNameById($questionModel->create_user), array("default/userinfo", 'user_id' => $questionModel->create_user), array('data-id' => $questionModel->create_user, 'class' => 'user-label', 'target' => '_blank')), $data->content) : $data->content;
                $this->widget('bootstrap.widgets.TbButton', array(
                    'type' => 'info',
                    'label' => (strlen($questionModel->title) > 40) ? mb_substr($questionModel->title, 0, 40, 'utf-8') . '...' : $questionModel->title,
                    'type' => 'link',
                    'url' => $this->createUrl("answer", array("id" => $answerModel->id)),
                ));
            } else if (($data->notification_type == "comment")) {
                $commentModel = Comment::model()->findByPk($data->pk_id);
                $parentModel = Comment::model()->findByPk($commentModel->parent_id);
                $dataUrl = Yii::app()->controller->createUrl($commentModel->model, array("id" => $commentModel->pk_id, "pk_id" => $data->id));
                echo (Yii::app()->user->id == $parentModel->user_id) ? str_replace("你", CHtml::tag("img", array("style" => "margin:0 4px;", "height" => "20", "width" => "20", "src" => $this->createUrl("getimage", array("id" => $parentModel->user_id, "type" => "avatar")))) . CHtml::link(User::getNameById($parentModel->user_id), array("default/userinfo", 'user_id' => $parentModel->user_id), array('data-id' => $parentModel->user_id, 'class' => 'user-label', 'target' => '_blank')), $data->content) : $data->content;
                $this->widget('bootstrap.widgets.TbButton', array(
                    'type' => 'info',
                    'label' => (strlen($parentModel->content) > 40) ? mb_substr($parentModel->content, 0, 40, 'utf-8') . '...' : $parentModel->content,
                    'type' => 'link',
                    'url' => $dataUrl,
                ));
            }
        }
        ?></span><span class="profile-setion-time pull-right"><?php echo Comment::model()->timeintval($data->send_time); ?></span>
        <?php
        if ($data->notification_type == "createanswer") {
            $content = strlen(strip_tags($answerModel->content)) > 120 ? mb_substr(strip_tags($answerModel->content), 0, 120, 'utf-8') . '...' : $answerModel->content;
            echo "<p>" . $content . "</p>";
        } else if ($data->notification_type == "question") {
            $content = strlen(strip_tags($answerModel->content)) > 120 ? mb_substr(strip_tags($answerModel->content), 0, 120, 'utf-8') . '...' : $answerModel->content;
            echo "<p>" . $content . "</p>";
        } else if (($data->notification_type == "answer") || ($data->notification_type == "article") || ($data->notification_type == "comment") || ($data->notification_type == "quesion")) {
            echo "<p>" . Comment::model()->findByPk($data->pk_id)->content . "</p>";
        } else if(in_array($data->notification_type, array("reportanswer", "reportquestion", "reportarticle"))){
            echo "<p>".$content. "</p>";
        }
        ?>
</div>