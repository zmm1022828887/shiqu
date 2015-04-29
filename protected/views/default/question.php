<style>
    .content{margin: 0 auto; width: 1030px; }
    .content .content-left{width:720px;position: relative; display: inline-block;} 
    .content .sidebar{width:260px; border-width: 2px; border-style: solid;border-top: 0;background-color: #fff; display: inline-block;height: 260px;background-color: #FAFAFA;}
    .content .pic{display: inline-block; position: absolute; left: 210px; top:0;padding: 10px;}
    .content .content-right{display: inline-block; width: 280px; float: right;}
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
     #question-answer{text-align: center;}
    .answer-opt a{color:#ccc;}
    .answer-opt a:hover{color:#0088cc;}
    .answer-opt .user-label *{color:#000;}
</style>
<?php 
$this->pageTitle = $model->title." - " . Yii::app()->name;
$this->widget('bootstrap.widgets.TbBreadcrumbs', array('links' =>  array("问题" => array("allquestion"),$model->title)));
?>
<div class="content clearfix">
    <div class="content-left">
   
        <div class="question-body clearfix">
            <div class="pull-left vote-number clearfix">
                <div class="pull-left opt-list" style="width: 50px;"> <a  rel="tooltip" data-original-title="<?php echo Vote::model()->count("opinion=0 and model=:model and pk_id=:pk_id and create_user=:create_user", array(":create_user" => Yii::app()->user->id, ":pk_id" => $model->id, ":model" => "question")) > 0 ? '取消赞同' : '赞同'; ?>"  class="btn btn-mini <?php echo Vote::model()->count("opinion=0  and pk_id=:pk_id and create_user=:create_user and model=:model", array(":model" => "question", ":create_user" => Yii::app()->user->id, ":pk_id" => $model->id)) > 0 ? 'active' : ''; ?>" name="<?php echo (Yii::app()->user->isGuest) ? 'noLogin' : 'agreeeAnswer'; ?>" data-model="question" data-pk="<?php echo $model->id; ?>"><i class="icon icon-arrow-up"></i><br/><span class="count"><?php echo Vote::model()->count("opinion=0 and model=:model and pk_id=:pk_id", array(":model" => "question", ":pk_id" => $model->id)); ?></span></a> <br/><a class="btn btn-mini <?php echo Vote::model()->count("opinion=1 and model=:model and pk_id=:pk_id and create_user=:create_user", array(":model" => "question", ":create_user" => Yii::app()->user->id, ":pk_id" => $model->id)) > 0 ? 'active' : ''; ?>" rel="tooltip" data-original-title="<?php echo Vote::model()->count("opinion=1 and pk_id=:pk_id and model=:model and create_user=:create_user", array(":create_user" => Yii::app()->user->id, ":pk_id" => $model->id,":model"=>"question")) > 0 ? '取消反对' : '反对，不会显示你的姓名'; ?>"  data-placement="bottom" name="<?php echo (Yii::app()->user->isGuest) ? 'noLogin' : 'disagreeeAnswer'; ?>"   data-model="question" data-pk="<?php echo $model->id; ?>"><i style="font-size:14px;" class="icon icon-arrow-down"></i></a></div>
            </div>
            <div class="question-content" style="margin-left:50px;">
                <p><?php $topicArray = explode(",",trim($model->topic_ids,","));?><?php for($i=0;$i<count($topicArray);$i++){?>
                 <?php  $topicModel = Topic::model()->findByPk($topicArray[$i]);?>
                    <?php if($topicModel){?>
                <a href="javascropt:;" data-id="<?php echo $topicArray[$i];?>" class="topic-label"><span class="label"><?php echo $topicModel->name;?><span></a>
                    <?php }?>
                <?php }?>           
                 <a  href="javascript:;" onclick="report(<?php echo $model->id;?>, 'question')" title="举报" class="pull-right"><i class="icon-flag" style="margin-right: 4px;"></i> 举报</a></p>
                <h3><?php echo $model->title; ?></h3>
                <p class="clearfix " style="font-size: 12px;height: 24px;line-height: 24px;"><span class="pull-left">     
                        <a href='<?php echo $this->createUrl("userinfo", array("user_id" => $model->create_user)); ?>' class="user-label" data-id="<?php echo  $model->create_user;?>" title="<?php echo User::getNameById($model->create_user); ?>"><?php echo User::getNameById($model->create_user); ?></a><span style="color:#ccc;margin-left: 10px;"><?php echo Comment::timeintval($model->create_time);?> 提问</span></span><span class="pull-right"><a class="user-label" href="javascript:;" data-id="<?php echo $model->create_user;?>"><img style="height: 24px;width: 24px;vertical-align:top;border-radius: 2px;" height="24" width="24" src="<?php echo  $this->createUrl("getimage",array("id"=>$model->create_user,"type"=>"avatar"));?>"></a></span></p>
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
               <?php if($model->content!=""){?><p><?php echo $model->content;?></p><?php }?>
            </div>
        </div>
        <fieldset class="clearfix">
            <legend style="margin-bottom:10px;font-size:20px;"><strong><?php echo Answer::model()->count("question_id=" . $model->id); ?>个回答</strong></legend>
        </fieldset>
        <?php $isAnswer = Answer::model()->find("question_id=:question_id and create_user=:create_user",array(":question_id"=>$model->id,":create_user"=>Yii::app()->user->id));?>
        <?php if(!isset($_GET["answer_id"]) && $isAnswer==null){?>
        <div class="list">
            <div class="list-comment">
                <div class="comment-list clearfix">
                    <div class="item clearfix" style="margin-bottom: 10px;position: relative;"> 
                        <div class="user" style="width:7%;float: left;">      
                            <div class="u-icon"> 
                                <?php if (Yii::app()->user->isGuest) { ?>
                                    <img height="30" width="30" class="gray" src="<?php echo $this->createUrl("getimage", array("id" => Yii::app()->user->id, "type" => "avatar")); ?>">
                                <?php } else { ?>
                                    <a   target="_blank" href="<?php echo $this->createUrl('default/userinfo', array('user_id' => Yii::app()->user->id)); ?>"> <img height="30" width="30"  src="<?php echo $this->createUrl("getimage", array("id" => Yii::app()->user->id, "type" => "avatar")); ?>"> </a>  
                                <? } ?>
                            </div>      
                        </div>  
                        <div class="clearfix box-section" style="width:93%;float: right;">
                            <div class="clearfix  right" style="display:block;left:0;clear:both;right:0;position: relative">
                                <?php
                                $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
                                    'id' => 'answer-form',
                                    'action' => $this->createUrl('createanswer', array("id" => $_GET["id"], "#" => "form")),
                                ));
                               
                                $modelAnswer = new Answer();
                                ?>
                                <?php echo $form->hiddenField($modelAnswer, 'question_id', array("value" => $model->id)); ?>
                                <?php if (Yii::app()->user->isGuest) { ?>
                                    <?php echo $form->textArea($modelAnswer, 'content', array('disabled' => Yii::app()->user->isGuest ? true : false, "style" => "width:97%;height:80px;resize: none;padding:10px;float:right;", "placeholder" => Yii::app()->user->isGuest ? "发表回答请先登陆" : "回答",)); ?>
                                <?php } else { ?>
                                    <?php echo $form->ckeditorRow($modelAnswer, 'content', array("style" => "width:97%;height:80px;resize: none;padding:10px;float:right;", "labelOptions" => array("label" => false))); ?>
                               
 <?php } ?>
                                <div class="pull-left" style="padding-top: 10px;">不知道答案？你还可以 
                                    <?php 
                                     $this->widget('bootstrap.widgets.TbButton', array(
                                    'buttonType' => 'button',
                                    'label' => '邀请回答',
                                     'size'=>'mini',
                                    'disabled' => Yii::app()->user->isGuest ? true : false,
                                    'htmlOptions' => array('onclick' => "$('#helpModal').modal('show');"),
                                    'type' =>'default',
                                ));
                                    ?>
                                </div>
                                <div class="pull-left" style="margin-left:340px;padding-top: 10px;">
                                <?php
                                
                                echo $form->checkBoxListInlineRow($modelAnswer, 'is_anonymous', array('1' => '匿名'),array('class' => 'pull-right','labelOptions'=>array("label"=>false), 'disabled'=>Yii::app()->user->isGuest ? true : false));
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php }?>
        <?php
        if(Answer::model()->search($model->id,'show')->totalItemCount>0){
        $this->widget('bootstrap.widgets.TbListView', array(
            'dataProvider' => Answer::model()->search($model->id,'show'),
            'itemView' => '../_answerlistview',
            'htmlOptions' => array('style' => 'padding-top:0px'),
            'id' => 'ajaxAnswerList',
            'template' => '{items}{pager}',
        ));
        }
        ?>
        <?php if($model->hide_answer_id!=""){?>
        <div style="border-bottom: 1px solid #e5e5e5;padding-bottom: 10px;">
            <a href="javascript:;"  onclick="js:$('#hideAnswerList').toggle()"><span id="question-collapsed-num"><?php echo count(explode(",", trim($model->hide_answer_id,",")));?></span> 个回答被折叠</a>（折叠是对不认真、不规范转载和严重错误的回答的一种处理方式。）
        </div>
          <?php
        $this->widget('bootstrap.widgets.TbListView', array(
            'dataProvider' => Answer::model()->search($model->id,'hide'),
            'itemView' => '../_answerlistview',
            'id' => 'hideAnswerList',
            'htmlOptions' => array('style' => 'padding-top:10px;display:none;'),
            'template' => '{items}{pager}',
        ));
        ?>
        <?php }?>
        <?php if($isAnswer!=null){?>
          <div id="question-answer">一个问题你只能回答一次，若没有被采纳，那么你可以对 <a href="<?php echo $this->createUrl("question",array("id"=>$model->id,"answer_id"=>$isAnswer->id,"#"=>"answer_".$isAnswer->id));?>">现有的回答</a> 进行修改</div>
        <?php }?>
    </div>
     <div class="content-right">
        <div id="setting-tabs" class="tabs-above">
                <?php if (Yii::app()->user->isGuest) { ?>
            <div class="alert alert-info"><?php echo Sys::model()->find()->site_desc;?></div>
                <?php }?>
            <fieldset>
                <legend style="margin-bottom: 10px;">相似问题</legend> 
            </fieldset>
             <?php
            $topicArray = explode(",",trim($model->topic_ids,","));
    $criteria = new CDbCriteria;
    for($i=0;$i<count($topicArray);$i++){
          $criteria->addSearchCondition("topic_ids",",".$topicArray[$i].",",true,"or");
    }
    $j=0;
    $criteria->order = "create_time desc";
    $criteria->addCondition("id!=".$model->id);
    $questionModel = Question::model()->findAll($criteria);
    $count = array();
    if (!empty($questionModel)) {
        echo '<table><tbody>';
        foreach ($questionModel as $question) {
            $questionModel = Question::model()->findByPk($question['id']);
                $j++;
                if ($j == 11) {
                    break;
                }
                ?>
                <tr><td style="padding-left:0px;"><a title="<?php echo $questionModel->title; ?>" href="<?php echo $this->createUrl("question", array("id" => $questionModel->id)); ?>"><?php echo $questionModel->title; ?></a><span style="color:#ccc;margin-left:0;">(共<?php echo Answer::model()->count("question_id = :question_id",array(":question_id"=>$questionModel->id)); ?>个回答,<?php echo (Question::model()->count("answer_id=".$questionModel->id)>0) ?  "已解决":"待解决"; ?>)</span></td></tr>
                <?php
        }
        echo '</tbody></table>';
    }else{
        echo "<div class='alert alert-info'>暂无相似问题</div>";
    }
    ?>
          <fieldset>
                <legend style="margin-bottom: 10px;">问题状态</legend>
          </fieldset>
            <p style="color: #ccc">
                <?php $answerModel = Answer::model()->find("question_id = :question_id order by create_time desc",array(":question_id"=>$model->id));?>
                <span style="display: block;">最近活动于<?php echo ($answerModel==NULL) ? Comment::timeintval($model->update_time): Comment::timeintval($answerModel->create_time);?>,<?php echo $model->answer_id!=0 ?  "已解决":"待解决"; ?></span>
                <span>被浏览<?php echo $model->view_count;?>次</span>
            </p>
        </div>
    </div>
</div>
  <?php if (!Yii::app()->user->isGuest) { ?>
<?php $this->beginWidget('bootstrap.widgets.TbModal', array('id' => 'helpModal', 'options' => array("backdrop" => 'static'), 'fade' => false)); ?>
<div class="modal-header">
    <a class="close" data-dismiss="modal">×</a>
    <h4 class="modal-title">邀请</h4> 
</div>
<div class="modal-body clearfix" style="overflow-y: visible;">
        <?php $this->renderPartial('../_askforhelpform',array('question'=>$model)); ?>
</div>
<div class="modal-footer" style="text-align: center;">
    <?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'button',
        'type' => 'info',
        'label' => '确认',
        'htmlOptions' => array('id' => 'submitHelp'),
    ));
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'button',
        'label' => '关闭',
        'htmlOptions' => array("data-dismiss" => "modal"),
    ));
    ?>
</div>
<?php $this->endWidget(); ?>

<?php $this->beginWidget('bootstrap.widgets.TbModal', array('id' => 'collapsedModal', 'options' => array("backdrop" => 'static'), 'fade' => false)); ?>
<div class="modal-header">
    <a class="close" data-dismiss="modal">×</a>
    <h4 class="modal-title">折叠回答</h4> 
</div>
 <?php
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => 'collapsed-answer-form',
        'type' => 'horizontal',
        'action' => $this->createUrl("collapsedanswer"),
    ));
    ?>
<div class="modal-body clearfix" style="overflow-y: visible;">
     <?php
    $answerModel = new Answer();
    echo $form->hiddenField($answerModel, 'id',array('id'=>"answer_id"));
    echo $form->textAreaRow($answerModel, 'hide_reason',array('hint'=>'填写折叠原因后会用折叠原因覆盖所选的回答'));
    ?>
    <p id='collapsed-answer-tip'>确定要取消折叠吗？</p>
</div>
<div class="modal-footer" style="text-align: center;">
    <?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'submit',
        'type' => 'info',
        'label' => '确认',
    ));
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'button',
        'label' => '关闭',
        'htmlOptions' => array("data-dismiss" => "modal"),
    ));
    ?>
</div>
<?php $this->endWidget(); ?>
<?php $this->endWidget(); ?>
<?php }?>
<script>
    var agreeVoteUrl = "<?php echo $this->createUrl("agreeavote"); ?>";
    var disagreeVoteUrl = "<?php echo $this->createUrl("disagreevote"); ?>";
    var acceptedUrl = "<?php echo $this->createUrl("acceptedanswer"); ?>";
    var changeCommentUrl = "<?php echo $this->createUrl("changecomment"); ?>";
    var deleteAnswersUrl = "<?php echo $this->createUrl("deleteanswers"); ?>";
    var isLogin = "<?php echo Yii::app()->user->isGuest ? "false":"true"; ?>";
     function report(pk,type){
            if(isLogin=="false"){
                 $("[name='noLogin']").trigger("click");
                  return false;
            }
            $('#reportModal').modal('show');
            $('#reportModal').find(".modal-title").text(type=="question" ? "为什么举报该问题？":"为什么举报该回答？");
            $("#Message_report_content").hide();
            $("#Message_report_uid").val(pk);
            $("#Message_report_model").val(type);
            return false;
        }
        
    function changeComment(answerId,type){
                    $.post(changeCommentUrl, {'answerId': answerId,'type':type}, function(data) {
                        if(data=="ok"){
                              $.fn.yiiListView.update("ajaxAnswerList");
                        }else{
                             alert("删除失败");
                        }
                    });
    }
     function deleteAnswer(answerId){
                 
         if(window.confirm("删除回答的同时，该回答下面的评论也会全部被删除，确定要删除吗？")){
                    $.post(deleteAnswersUrl, {'answerId': answerId}, function(data) {
                        if(data=="ok"){
                              $.fn.yiiListView.update("ajaxAnswerList");
                        }else{
                             alert("删除失败");
                        }
                    });
                }
    }
    function collapsedAnswer(answerId,type){
          $('#collapsedModal').modal('show');
            $('#answer_id').val(answerId);
            if(type==0){
              $('#collapsedModal').find(".modal-title").text("折叠回答");
              $('#collapsed-answer-tip').hide();
              $('#collapsed-answer-tip').prev().show();
            }else{
              $('#collapsedModal').find(".modal-title").text("取消折叠回答");
              $('#collapsed-answer-tip').show();
              $('#collapsed-answer-tip').prev().hide(); 
            }
    }
    $(document).ready(function() {
        $(document).delegate(".all", 'click', function() {
            $(this).parent(".vote-info").find(".vote-all").show();
            $(this).parent(".vote-info").find(".vote-some").hide();
            $(this).text("赞同");
        });
        $(document).delegate("#submitcomment", 'click', function() {
            $('#Answer_content').val(CKEDITOR.instances.Answer_content.getData());  //否则运用ajaxSubmit的时候取不到内容的值
            if ($.trim($("#Answer_content").val()) == "") {
                alert("请输入相应的回答");
            } else {
                $("#answer-form").submit();
            }
        });
        $(document).delegate("a[name='agreeeAnswer']", 'click', function() {
            var self = $(this);
            var pk_id = self.attr("data-pk");
            var model = self.attr("data-model");
            $.post(agreeVoteUrl, {'pk_id': pk_id,'model':model}, function(data) {
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
        $(document).delegate("a[name='disagreeeAnswer']", 'click', function() {
            var self = $(this);
            var pk_id = self.attr("data-pk");
            var model = self.attr("data-model");
            $.post(disagreeVoteUrl, {'pk_id': pk_id,'model':model}, function(data) {
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
            $(this).find("a[name='accepted']").show();
        });
        $(document).delegate(".vote-list", 'mouseleave', function() {
            $(this).find("a[name='accepted']").hide();
        });
        $(document).delegate("a[name='accepted']", 'click', function() {
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
       $("#submitHelp").click(function() {
            var $form = $("#request-form");
            $.ajax({
                url: $form.attr("action"),
                type: 'post',
                data: $form.serialize(),
                dataType: 'json',
                success: function(data) {
                    if ((data.to_user || data.user_mame || data.create_user ||  data.question_id)) {
                        $.each(data, function(k, v) {
                           if ($('#Request_' + k).parents('.control-group').find('.error').length > 0) {
                                $('#Request_' + k).parents('.control-group').find('.error').remove();
                                $('#Request_' + k).parents('.control-group').removeClass('error');
                            }
                            if (k == "to_user") {
                                $("#askUser").after('<span class="help-inline error" id="Requestuser_name_em_" style="">' + v + '</span>').parents(".control-group").addClass('error');
                            } else {
                                $("#Request_" + k).after('<span class="help-inline error" id="Message_' + k + '_em_" style="">' + v + '</span>').parents(".control-group").addClass('error');
                            }
                        });
                    } else {
                        location.reload();
                    }
                }
            });
        });
    });
</script>