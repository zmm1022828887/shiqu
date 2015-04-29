<?php $modelSys = Sys::model()->find(); ?>
<div class="footer"><?php //echo Yii::getVersion()                         ?>
    <div class="email" style="float: left">管理员邮箱：<a href="mailto:<?php echo $modelSys->mail; ?>" class="red"><?php echo $modelSys->mail; ?></a></div>
    <div class="copyright"  style="float: right">版权所有&copy; ：<a href="<?php echo $this->createUrl("about");?>" class="red"><?php echo $modelSys->copyright; ?></a></div>
</div><!-- footer -->
<?php $this->renderPartial('../_topicmodal', array("topicModel" => new Topic, 'action' => $this->createUrl("/default/createtopic"))); ?>
<?php $this->renderPartial('../_questionmodal', array("questionModel" => new Question, 'action' => $this->createUrl("/default/createquestion"))); ?>
<?php $this->beginWidget('bootstrap.widgets.TbModal', array('id' => 'loginModal', 'options' => array("backdrop" => 'static'), 'fade' => false)); ?>
<div class="modal-header">
    <a class="close" data-dismiss="modal">×</a>
    <h4 class="modal-title" id="myModalLabel">登陆</h4> 
</div>
<div class="modal-body">

    <?php
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'type' => 'horizontal',
        'id' => 'loginForm',
        'method' => 'post',
        'clientOptions' => array(
            'validateOnSubmit' => true,
            'validateOnChange' => false,
        ),
        'enableAjaxValidation' => true,
        'action' => $this->createUrl("default/login"),
    ));
    $model = new PloginForm;
    ?>
    <?php echo $form->textFieldRow($model, 'username', array('placeholder' => '账号')); ?>
    <?php echo $form->passwordFieldRow($model, 'password', array('placeholder' => '密码')); ?>
    <div class="control-group">
        <label class="control-label required" for="PloginForm_password"></label>
        <div class="controls"> <?php echo $form->checkbox($model, 'rememberMe', array('style' => 'margin-top:0;')); ?><label for='PloginForm_rememberMe' style="display:inline;margin:6px;"><?php echo $model->getAttributeLabel('rememberMe'); ?></label></div></div>
    <?php $form->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit', 'type' => 'info', 'label' => '登陆', 'htmlOptions' => array('class' => 'controls'))); ?>
    <?php $this->endWidget(); ?>
    <?php
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'type' => 'horizontal',
        'id' => 'registerForm',
        'method' => 'post',
        'clientOptions' => array(
            'validateOnSubmit' => true,
            'validateOnChange' => false,
        ),
        'enableAjaxValidation' => true,
        'action' => $this->createUrl("default/check"),
    ));
    $RegisterModel = new RegisterForm;
    ?>
    <?php echo $form->textFieldRow($RegisterModel, 'username', array('placeholder' => '账号')); ?>
    <?php echo $form->passwordFieldRow($RegisterModel, 'password', array('placeholder' => '密码')); ?>
    <?php echo $form->passwordFieldRow($RegisterModel, 'repasword', array('placeholder' => '确认密码')); ?>
    <?php $form->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit', 'type' => 'info', 'label' => '注册', 'htmlOptions' => array('class' => 'controls'))); ?>
    <?php $this->endWidget(); ?>
</div>
<div class="modal-footer" style="background-color:#5a5a5a;border:none;">
    <span id="tipMessage"><span id="tipWord">还没有</span><?php echo CHtml::encode(Yii::app()->name); ?>账户？</span>
    <a href="javascript:;"  id="registerButton" style="margin-left: 10px;">立即注册</a>
    <a href="javascript:;" id="loginButton">立即登录</a>
</div>
<?php $this->endWidget(); ?>
<?php $this->beginWidget('bootstrap.widgets.TbModal', array('id' => 'messageModal', 'options' => array("backdrop" => 'static'), 'fade' => false)); ?>
<div class="modal-header">
    <a class="close" data-dismiss="modal">×</a>
    <h4 class="modal-title" id="myModalLabel">发送私信</h4> 
</div>
<div class="modal-body" style="max-height:500px;">
    <?php $this->renderPartial('../_messageform', array("model" => new Message, 'action' => $this->createUrl("/default/createmessage"))); ?>
</div>
<div class="modal-footer" style="text-align: center;">
    <?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'button',
        'type' => 'info',
        'label' => '发送',
        'htmlOptions' => array('id' => 'submitMessage'),
    ));
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'button',
        'label' => '关闭',
        'htmlOptions' => array("data-dismiss" => "modal"),
    ));
    ?>
</div>
<?php $this->endWidget(); ?>
<?php $this->beginWidget('bootstrap.widgets.TbModal', array('id' => 'reportModal', 'options' => array("backdrop" => 'static'), 'fade' => false)); ?>
<div class="modal-header">
    <a class="close" data-dismiss="modal">×</a>
    <h4 class="modal-title">为什么举报该用户？</h4> 
</div>
<div class="modal-body" style="max-height:500px;text-align: center;">
    <div class="form" style="padding-top:10px;width:400px;text-align: left;margin: 0 auto;">
        <?php
        $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
            'id' => 'report-form',
            'action' => $this->createUrl("createReport"),
        ));
        ?>
        <?php
        $messageModel = new Message();
        echo $form->radioButtonListInlineRow($messageModel, 'report_type', Message::model()->getReportType(), array('labelOptions' => array('label' => false)));
        echo $form->hiddenField($messageModel, 'report_uid');
        echo $form->hiddenField($messageModel, 'report_model',array('value'=>'user'));
        echo $form->textAreaRow($messageModel, 'report_content', array("style" => "width:400px;height:40px;padding:4px;resize:none", "placeholder" => "请填写举报原因", 'labelOptions' => array('label' => false)));
        ?>
        <?php $this->endWidget(); ?>
    </div>
</div><!-- form -->
<div class="modal-footer" style="text-align: center;">
    <?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'button',
        'type' => 'info',
        'label' => '发送',
        'htmlOptions' => array('id' => 'submitReport'),
    ));
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'button',
        'label' => '关闭',
        'htmlOptions' => array("data-dismiss" => "modal"),
    ));
    ?>
</div>
<?php $this->endWidget(); ?>
<?php $this->beginWidget('bootstrap.widgets.TbModal', array('id' => 'topicModal', 'options' => array("backdrop" => 'static'), 'fade' => false)); ?>
<div class="modal-header">
    <a class="close" data-dismiss="modal">×</a>
    <h4 class="modal-title" id="myModalLabel">创建话题</h4> 
</div>
<div class="modal-body" style="max-height:500px;">
    <?php $this->renderPartial('../_topicform', array("topicModel" => new Topic, "group_id" => $_GET["id"], 'action' => $this->createUrl("/default/createtopic"))); ?>
</div>
<div class="modal-footer" style="text-align: center;">
    <?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'button',
        'type' => 'info',
        'label' => '保存',
        'htmlOptions' => array('id' => 'submitTopic'),
    ));
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'button',
        'label' => '关闭',
        'htmlOptions' => array("data-dismiss" => "modal"),
    ));
    ?>
</div>
<?php $this->endWidget(); ?>
<style>
    .message-nav{position: relative;top:-2px;}
    .message-nav  li{float: left;width: 33%;}
    .message-nav  li a{

        display: inline-block;
        width: 100%;
        text-align: center;
        vertical-align: middle;
        position: relative;
        font-size: 20px;
        cursor: pointer;
        background: 0;
        border: 0;
        outline: 0;
        padding: 0;
        border:1px solid transparent;
        text-decoration: none;
        border-right: 1px solid #EEE;
        height: 40px;

        line-height: 40px;
    }
    .popup-footer a{color: #999;
                    display: inline-block;
                    padding: 6px 12px;}
    .empty-tips{position:absolute;top:120px;color:#000;text-align:center;width:100%;}

    /*用户卡片*/
    .user-label .popover,.topic-label .popover{
        max-width: 410px !important;
        width: 410px !important;
        font-weight:normal;
        cursor: default;
        text-align:left;
    }
    .user-label .popover .popover-title,.topic-label .popover .popover-title{
        display:none;
    }
    .user-label .popover .popover-content,.topic-label .popover .popover-content{
        padding:0;
        color:#000;
    }
    .card {
        width: 410px;
    }
    .card .upper {
        background: white;
        padding-left:96px;
        min-height: 80px;
    }
    .card .upper a.avatar-link {
        position: relative;
    }
    .card .upper a.avatar-link {
        position: relative;
    }
    .card .upper img.card-avatar {
        position: absolute;
        width: 80px !important;
        height: 80px !important;
        max-width: 80px !important;
        left: -96px;
        border-radius: 3px;
    }
    .card .lower .meta .item {
        float: left;
        padding: 0 16px;
        border-right: 1px solid #EEE;
        color: inherit;
        text-decoration: none;
    }
    .card .lower .meta .item .value, .card .lower .meta .item .key {
        display: block;
        text-align: center;
    }
    .card .lower .meta .item .value {
        font-size: 15px;
        font-weight: 700;
    }
    .card .lower .operation .zg-btn, .card .lower .operation .btn-white, .card .lower .operation button {
        float: right;
        height: 32px;
        margin-top: 8px;
        vertical-align: middle;
    }
    .card .lower {
        background: #FAFAFA;
    }
    .card .upper,.card .lower {
        border:14px solid transparent !important;
    }
</style>
<script>
    var attentionUserUrl = '<?php echo $this->createUrl("attentionuser"); ?>';  //关注用户操作
    var createAttentionUrl = '<?php echo $this->createUrl("createattention"); ?>';//加入小组操作
    var loveTopicUrl = '<?php echo $this->createUrl("attentiontopic"); ?>';  //固定话题
    var blockUserUrl = '<?php echo $this->createUrl("blockuser"); ?>';//屏蔽用户操作
    var editGroupUrl = "<?php echo $this->createUrl("editgroup"); ?>"; //编辑小组action
    var editTopicUrl = "<?php echo $this->createUrl("edittopic"); ?>"; //编辑话题action
    var updateGroupUrl = "<?php echo $this->createUrl("updategroup", array("id" => "pk")); ?>"; //修改小组action
    var updateTopicUrl = "<?php echo $this->createUrl("updatetopic", array("id" => "pk")); ?>"; //修改话题action
    var typeAction = "<?php echo (isset($_GET["type"]) && ($_GET["type"] == "group")) ? "personal" : "allgroup"; ?>";
    var createReplyUrl = '<?php echo $this->createUrl("/default/createreply"); ?>';
    var checkGroupuserUrl = '<?php echo $this->createUrl("/default/checktopicuser"); ?>';
    var userId = '<?php echo Yii::app()->user->id; ?>';
    var userName = '<?php echo Yii::app()->user->name; ?>';
    var userTitle = '<?php echo Yii::app()->user->name . " - " . $this->title; ?>';
    var userAvatar = '<?php echo $this->createUrl("getimage", array("id" => Yii::app()->user->id, "type" => "avatar")); ?>';
    var userInfoUrl = '<?php echo $this->createUrl("userinfo", array("user_id" => Yii::app()->user->id)); ?>';
    var groupId = '<?php echo $_GET["id"]; ?>';
    var messageSettingUrl = '<?php echo $this->createUrl("setting", array("type" => "message")); ?>';
    var notReadMessageUrl = <?php echo Message::getJSONData(); ?>;
    var notReadNotificationUrl = <?php echo Notification::getJSONData("all"); ?>;
    var notReadCommentUrl = <?php echo Notification::getJSONData(); ?>;
    var notifyUrl = '<?php echo $this->createUrl("notify"); ?>';  //关注用户操作
    var deleteAnswerUrl = '<?php echo $this->createUrl("deleteanswer"); ?>';  //关注用户操作
    var cancelNotanonymousUrl = '<?php echo $this->createUrl("cancelnotanonymous"); ?>';
</script>
<script>
    function submitTopic() {
        $('#topicModal #topic-form').ajaxForm({
            complete: function(xhr) {
                var data = $.parseJSON(xhr.responseText);
                if ((data.name || data.logo)) {
                    $.each(data, function(k, v) {
                        if ($('#Topic_' + k).parents('.control-group').find('.error').length > 0) {
                            $('#Topic_' + k).parents('.control-group').find('.error').remove();
                            $('#Topic_' + k).parents('.control-group').removeClass('error');
                        }
                        $("#Topic_" + k).after('<span class="help-inline error" id="Topic_' + k + '_em_" style="">' + v + '</span>').parents(".control-group").addClass('error');
                    });
                } else {
                    $("#topicModal").modal("hide");
                    if (typeAction == "personal") {
                    } else {
                        $.fn.yiiListView.update("topic-listview");
                    }
                }
            }
        });
        $('#topicModal #topic-form').submit();
    }
    function submitQuestion() {
        $('#question-form').ajaxForm({
            complete: function(xhr) {
                var data = $.parseJSON(xhr.responseText);
                if ((data.title || data.topic_ids)) {
                    $.each(data, function(k, v) {
                        if ($('#Question_' + k).parents('.control-group').find('.error').length > 0) {
                            $('#Question_' + k).parents('.control-group').find('.error').remove();
                            $('#Question_' + k).parents('.control-group').removeClass('error');
                        }
                        $("#Question_" + k).after('<span class="help-inline error" id="Question_' + k + '_em_" style="">' + v + '</span>').parents(".control-group").addClass('error');
                    });
                } else {
                    location.href = location.href;

                }
            }
        });
        $('#question-form').submit();
    }
    $(document).ready(function() {
        $("#loginButton").hide();
        $("#registerForm").hide();

        $("#loginModal").on('shown', function() {
            $(this).find("input").val("");
            $(this).find(".control-group").removeClass("success");
            $(this).find(".control-group").removeClass("error");
            $(this).find(".help-inline").hide().removeClass("error");
        });
        $("#registerButton").live("click", function() {
            $("#loginForm").hide();
            $("#registerForm").show();
            $("#loginModal").find("h4").html("注册");
            $("#tipWord").html("已经有");
            $(this).hide();
            $("#loginButton").show();

        });
        $("#loginButton").live("click", function() {
            $("#loginForm").show();
            $("#registerForm").hide();
            $("#loginModal").find("h4").html("登录");
            $("#tipWord").html("还没有");
            $(this).hide();
            $("#registerButton").show();

        });
        $(document).delegate("a[data-op='updateTopic']", 'click', function() {
            var self = $(this);
            var topic_id = self.parents("tr").attr("topic-id");
            $('#topicModal').modal('show');
            $('#topicModal').find(".modal-title").html("修改话题");
            $.ajax({
                url: editTopicUrl,
                type: 'post',
                data: {'topic_id': topic_id},
                dataType: 'json',
                success: function(data) {
                    $("#topicModal #Topic_name").val(data.name);
                    $("#topicModal #Topic_desc").val(data.desc);
                    $("#topicModal #Topic_parent_id").val(data.parent_id);
                    newupdateToipcUrl = updateTopicUrl.replace("pk", topic_id);
                    $("#topicModal form").attr({"action": newupdateToipcUrl});
                }
            });
            return false;
        });
        $("#topicModal,#questionModal").on('shown', function() {
            $(this).find("form").get(0).reset();
            $(this).find(".control-group").removeClass("success");
            $(this).find(".control-group").removeClass("error");
            $(this).find(".help-inline").hide().removeClass("error");
        });
        $("#submitTopic").click(function() {
            var $forms = $("#topic-form");
            $('#Topic_desc').val(CKEDITOR.instances.Topic_desc.getData());  //否则运用ajaxSubmit的时候取不到内容的值
            $.ajax({
                url: $forms.attr("action"),
                type: 'post',
                data: $forms.serialize(),
                dataType: 'json',
                success: function(data) {
                    if ((data.title)) {
                        $.each(data, function(k, v) {
                            if ($('#Topic_' + k).parents('.control-group').find('.error').length > 0) {
                                $('#Topic_' + k).parents('.control-group').find('.error').remove();
                                $('#Topic_' + k).parents('.control-group').removeClass('error');
                            }
                            $("#Topic_" + k).after('<span class="help-inline error" id="Topic_' + k + '_em_" style="">' + v + '</span>').parents(".control-group").addClass('error');
                        });
                    } else {
                        location.href = location.href;
                    }
                }
            });
        });
        $(document).delegate(".user-label", 'mouseenter', function() {
           var $tr = $(this);
            var id = $(this).attr('data-id');
            $tr.popover({
                html: true,
                container: $tr,
                content: function() {
                    return $.ajax({url: "<?php echo Yii::app()->createUrl('/default/userlabel'); ?>",
                        dataType: 'html',
                        data: {'id': id},
                        type: 'post',
                        async: false}).responseText;
                }
            }).popover('show');
        });
        $(document).delegate(".user-label", 'mouseleave', function() {
            if ($(this).find('.popover').length > 0) {
                $(this).find('.popover').remove();
            }
        });
        $(document).delegate(".topic-label", 'mouseenter', function() {
            var $tr = $(this);
            var id = $(this).attr('data-id');
            $tr.popover({
                html: true,
                container: $tr,
                content: function() {
                    return $.ajax({url: "<?php echo Yii::app()->createUrl('/default/topiclabel'); ?>",
                        dataType: 'html',
                        data: {'id': id},
                        type: 'post',
                        async: false}).responseText;
                }
            }).popover('show');
        });
        $(document).delegate(".topic-label", 'mouseleave', function() {
            if ($(this).find('.popover').length > 0) {
                $(this).find('.popover').remove();
            }
        });
        $("[name='CreteTopic']").click(function() {
            $('#topicModal').modal('show');
            $('#topicModal').find(".modal-title").html("新建话题");
            $('#topicModal').find("form").get(0).reset();
            $("#topicModal #Group_user_name").parents(".control-group").hide();
            return false;
        });
        $("[name='ask']").click(function() {
            $('#questionModal').modal('show');
            $('#questionModal').find(".modal-title").html("提问");
            $('#questionModal').find("form").get(0).reset();
            return false;
        });
        $("[name='showWealth']").click(function() {
            $('#wealthModal').modal('show');
            return false;
        });
        $("#messagePopover").hover(function() {
            var content = '<ul class="message-nav nav-tabs"><li class="active"><a data-toggle="tab" href="#message_tab_1" class="icon-envelop" style="border-left:none;"></a></li><li><a data-toggle="tab" href="#message_tab_2" class="icon-users"></a></li><li class=""><a data-toggle="tab" href="#message_tab_3" class="icon-bullhorn"  style="border-right:none;"></a></li></ul>';
            var footer = '<div class="popup-footer"><a href="' + notifyUrl + '" class="pull-right">查看全部 »</a><a href="' + messageSettingUrl + '"  class="pull-left" title="消息设置"><i class="icon-cog-2"></i></a></div>';
            var $dom1, $dom2, $dom3s;
            $dom1 = '<div id="portal_wrap" class="clearfix">';
            $.each(notReadMessageUrl, function(k, v) {
                $dom1 += '<a  style="display:inline-block;width:100%;border-bottom: 1px solid #dbdbdb;border-top: 1px solid #FFFFFF;cursor: pointer;padding: 5px 0;" class="message-item"  href="' + v.dataUrl + '"><span style="display:inline-block;width:100%;height:20px;line-height:20px;"><span class="pull-left" style="padding: 0 5px;">' + v.createUser + '</span><span class="pull-right" style="color:#ff0000;padding: 0 5px;">' + v.createTime + '</span></span><span style="padding: 0 5px;">' + v.content + '</span></a>';
            });
            $dom1 += '</div>';
            $dom2 = '<div id="portal_wrap" class="clearfix">';
            $.each(notReadNotificationUrl, function(k, v) {
                if (v.type == "attention") {
                    $dom2 += '<a  style="display:inline-block;width:100%;border-bottom: 1px solid #dbdbdb;border-top: 1px solid #FFFFFF;cursor: pointer;padding: 5px 0;" class="message-item"  href="' + v.dataUrl + '"><span style="display:inline-block;width:100%;height:20px;line-height:20px;"><img src="' + v.avatarSrc + '" height="20" width="20" class="pull-left" style="padding-left:4px;"/><span class="pull-left" style="padding: 0 5px;">' + v.createUser + '</span>' + v.content + '你<span class="pull-right" style="color:#ff0000;padding: 0 5px;">' + v.createTime + '</span></span></a>';
                } else {
                    $dom2 += '<a  style="display:inline-block;width:100%;border-bottom: 1px solid #dbdbdb;border-top: 1px solid #FFFFFF;cursor: pointer;padding: 5px 0;" class="message-item"  href="' + v.reportUrl + '"><span style="display:inline-block;width:100%;height:20px;line-height:20px;"><img src="' + v.avatarSrc + '" height="20" width="20" class="pull-left" style="padding-left:4px;"/><span class="pull-left" style="padding: 0 5px;">' + v.createUser + '</span>举报了<img src="' + v.reportAvatar + '" height="20" width="20" style="display:inline-block;padding-left:4px;"/>' + v.reportUser + '<span class="pull-right" style="color:#ff0000;padding: 0 5px;">' + v.createTime + '</span></span><span style="display:inline-block;padding-left:4px;">' + v.content + '</span></a>';
                }
            });
            $dom2 += '</div>';
            $dom3 = '<div id="portal_wrap" class="clearfix">';
            $.each(notReadCommentUrl, function(k, v) {
                $dom3 += '<a  style="display:inline-block;width:100%;border-bottom: 1px solid #dbdbdb;border-top: 1px solid #FFFFFF;cursor: pointer;padding: 5px 0;" class="message-item"  href="' + v.dataUrl + '"><span style="display:inline-block;width:100%;height:20px;line-height:20px;"><img src="' + v.avatarSrc + '" height="20" width="20" class="pull-left" style="padding-left:4px;"/><span class="pull-left" style="padding: 0 5px;">' + v.createUser + '</span>' + v.content + '<span class="pull-right" style="color:#ff0000;padding: 0 5px;">' + v.createTime + '</span></span><span style="padding: 0 5px;">' + v.desc + '</span></a>';
            });
            $dom3 += '</div>';
            if (notReadMessageUrl.length == 0) {
                var content1 = '<div class="empty-tips">暂无未读私信</div>';
            } else {
                var content1 = $dom1;
            }
            if (notReadNotificationUrl.length == 0) {
                if (userName == "admin") {
                    var content2 = '<div class="empty-tips">有人关注你或者举报信息时会显示在这里</div>';
                } else {
                    var content2 = '<div class="empty-tips">有人关注你时会显示在这里</div>';
                }
            } else {
                var content2 = $dom2;
            }
            if (notReadCommentUrl.length == 0) {
                var content3 = '<div class="empty-tips">有人评论你的文章和回答、对你的话题进行提问<br />和回答你的问题会显示在这里</div>';
            } else {
                var content3 = $dom3;
            }
            var body = '<div class="tab-content" style="height:300px;position:relative;border-bottom:1px solid #eee;overflow:auto;"><div id="message_tab_1" class="tab-pane active fade in">' + content1 + '</div><div id="message_tab_2" class="tab-pane fade">' + content2 + '</div><div id="message_tab_3" class="tab-pane fade">' + content3 + '</div></div>';
            $(this).popover({
                placement: 'bottom', // top, bottom, left or right
                html: 'true', //needed to show html of course
                template: '<div class="popover" name="pophide" style="width:300px;max-width:300px"><div class="arrow" style="z-index:1000;"></div><div class="popover-inner"><div class="popover-content" style="padding:0;"><p></p></div></div></div>',
                content: content + body + footer,
            });
        });
        $(document).delegate("a[name='reply']", 'click', function() {
            $('#messageModal').modal('show');
            $("#Message_user_name").attr("value", $(this).attr("data-username"));
            $("#Message_user_name").attr("readonly", "readonly");
            $("#Message_to_uid").val($(this).attr("data-uid"));
            return false;
        });
        $("[name='report']").click(function() {
            $('#reportModal').modal('show');
            $("#Message_report_content").hide();
            $("#Message_report_uid").val($(this).attr("data-uid"));
            return false;
        });
        $("[name='joinTopic']").live("click", function() {
            self = $(this);
            var topic_id = self.attr("data-topicid");
            var count = $("#joinTotal").text();
            $.ajax({
                url: createAttentionUrl,
                type: 'post',
                data: {'topic_id': topic_id},
                success: function(data) {
                    if (data == "OK") {
                        self.text('取消关注');
                        count++;

                        $("#joinTotal").text(count);
                        var li = '<li data-uid="' + userId + '"><div class="pic"><a  data-id="' + userId + '" class="user-label" href="javascript:;"><img height="50" width="50" src="' + userAvatar + '" alt="' + userTitle + '"></a></div><div class="name"><a title="' + userTitle + '" target="_blank" href="' + userInfoUrl + '">' + userName + '</a></div></li>';
                        if ($("#topicuser-list").length == 0) {
                            $(".group-user").append("<div class='member-list' id='topicuser-list'><ul>" + li + "</ul></div>");
                        } else if ($("#topicuser-list").find("li").length == 0) {
                            $("#topicuser-list ul").append(li);
                        } else {
                            $("#topicuser-list ul").append(li);
                            $("#topicuser-list").find("li[data-uid='" + userId + "']").insertBefore($("#topicuser-list ul").find("li:eq(0)"));
                        }
                        if ($("#topic-grid").length == 0) {
                            if ($("#topicuser-grid").length == 0) {
                                $(".group-user").append("<div class='member-list' id='topicuser-grid'><ul>" + li + "</ul></div>");
                            } else if ($("#topicuser-grid").find("li").length == 0) {
                                $("#topicuser-grid ul").append(li);
                            } else {
                                $("#topicuser-grid ul").append(li);
                                $("#topicuser-grid").find("li[data-uid='" + userId + "']").insertBefore($("#topicuser-grid ul").find("li:eq(0)"));
                            }
                        }
                    } else {
                        self.text('关注');
                        count--;
                        $("#joinTotal").text(count);
                        $("#topicuser-list").find("li[data-uid=" + userId + "]").remove();
                        if ($("#topicuser-grid").length > 0)
                            $("#topicuser-grid").find("li[data-uid=" + userId + "]").remove();
                    }
                }
            });
            return false;
        });
        $("[name='loveTopic']").live("click", function() {
            var self = $(this);
            var topic_id = self.attr("data-topicid");
            $.ajax({
                url: loveTopicUrl,
                type: 'post',
                dataType: 'json',
                data: {'topic_id': topic_id},
                success: function(data) {
                    if ($("#topic-list").length > 0)
                        $.fn.yiiListView.update("topic-list");
                    if ($("#topic-item").length > 0)
                        $.fn.yiiListView.update("topic-item");
                    if ($("#topic-count").length > 0)
                        $("#topic-count").text(data.count);
                    if ($("#topic-title").length > 0) {
                        var title = (self.attr("title") == "取消固定") ? "固定话题" : "取消固定";
                        self.html("<i class='icon-pushpin'></i>" + title);
                        self.attr("title", title);
                    }

                }
            });
            return false;
        });
        $("[name='Notanonymous']").live("click", function() {
            var self = $(this);
            var answer_id = self.attr("data-answerid");
            $.ajax({
                url: cancelNotanonymousUrl,
                type: 'post',
                dataType: 'json',
                data: {'answer_id': answer_id},
                success: function(data) {
                    if (data.message == "ok") {
                        location.reload();
                    } else {
                        alert(data.tip);
                    }
                }
            });
            return false;
        });
        $("[name='attention']").live("click", function() {
            var self = $(this);
            var user_id = self.attr("data-uid");
            $.post(attentionUserUrl, {'user_id': user_id}, function(data) {
                if (data == "FAILE") {
                    alert("关注操作失败，TA已经把您屏蔽了");
                } else if (data == "DENGER") {
                    alert("自己不能关注自己");
                } else
                    self.text(data);
            });
            return false;
        });
        $("[data-action='deleteanswer']").live("click", function() {
            var self = $(this);
            if (window.confirm("删除回答时，此回答下的评论和回复也会全部删除，确定要删除所选的回答吗?")) {
            var answer_id = self.attr("data-answerid");
            $.post(deleteAnswerUrl, {'answer_id': answer_id}, function(data) {
                if (data == "false") {
                    alert("删除失败");
                } else {
                    if ($("#answerListView").length > 0)
                        $.fn.yiiListView.update("answerListView");
                }
            });
        }
            return false;
        });
        $("[name='block']").click(function() {
            var self = $(this);
            var user_id = self.attr("data-uid");
            var block_value = self.attr("block-value");
            var confirmText = (block_value == 1) ? "取消屏蔽用户后，对方将可以关注你、向你发私信，还可以查看你的公开信息" : "屏蔽用户后，对方将不能关注你、向你发私信，但仍然可以查看你的公开信息"
            if (window.confirm(confirmText)) {
                $.post(blockUserUrl, {'user_id': user_id}, function(data) {
                    if (data == "DENGER") {
                        alert("自己不能屏蔽自己");
                    } else
                        self.text(data);
                    var new_block_value = (block_value == 1) ? 0 : 1;
                    self.attr("block-value", new_block_value);
                });
            }
            return false;
        });
        $("[name='noLogin']").click(function() {
            $('#loginModal').modal('show');
            $('#loginButton').trigger("click");
            return false;
        });
        $("#messageModal").on('shown', function() {
            $(this).find("#Topic_title").val("");
            $(this).find(".control-group").removeClass("success");
            $(this).find(".control-group").removeClass("error");
            $(this).find(".help-inline").hide().removeClass("error");
        });
        $("#reportModal").find('input:radio[name="Message[report_type]"]').click(function() {
            if ($(this).val() != "4") {
                $("#Message_report_content").hide().val($(this).next().text())
            } else {
                $("#Message_report_content").show().val("");
            }
        });
        $("#submitReport").click(function() {
            var $form = $("#report-form");
            var val = $form.find('input:radio[name="Message[report_type]"]:checked').val();
            if (val == null) {
                alert("请选择举报原因!");
                return false;
            } else {
                if ($("#Message_report_content").val() == "") {
                    alert("请填写举报原因!");
                    return false;
                }
            }
            $.ajax({
                url: $form.attr("action"),
                type: 'post',
                data: $form.serialize(),
                dataType: 'json',
                success: function(data) {
                    if (data.type == "error") {
                        alert(data.message);
                    } else {
                        $("#reportModal").modal("hide");
                        alert(data.message);
                    }
                }
            });
        });
        $("#submitMessage").click(function() {
            var $form = $("#message-form");
            $.ajax({
                url: $form.attr("action"),
                type: 'post',
                data: $form.serialize(),
                dataType: 'json',
                success: function(data) {
                    if ((data.to_uid || data.content)) {
                        $.each(data, function(k, v) {
                            if ($('#Message_' + k).parents('.control-group').find('.error').length > 0) {
                                $('#Message_' + k).parents('.control-group').find('.error').remove();
                                $('#Message_' + k).parents('.control-group').removeClass('error');
                            }
                            if (k == "to_uid") {
                                $("#Message_user_name").after('<span class="help-inline error" id="Message_user_name_em_" style="">' + v + '</span>').parents(".control-group").addClass('error');
                            } else {
                                $("#Message_" + k).after('<span class="help-inline error" id="Message_' + k + '_em_" style="">' + v + '</span>').parents(".control-group").addClass('error');
                            }
                        });
                    } else {
                        $("#messageModal").modal("hide");
                        location.href = location.href;
                    }
                }
            });
        });
    });
</script>