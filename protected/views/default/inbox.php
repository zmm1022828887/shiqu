<style>
    .content{margin: 0 auto; width: 1030px; }
    .main-content{padding: 25px 0 50px 0;}
    .content .content-left{width: 690px; float: left;}
    /*右侧样式*/
    .content .content-right{width: 260px; float: right;}
    .content .content-right .content-sidebar h5{text-align: left;padding: 0; margin: 0;}
    .message-opt{position: absolute; right: 0; bottom: 4px;}
    .message-opt ul li{float: left;color:#bbb;padding: 0 3px;}

    .msg_type{ _zoom: 1;margin-top: -1px; position: relative;padding-top: 1px;margin-right: 2em;font-size: 14px;}
    .msg_type .private_list {border-bottom:1px solid #e5e5e5;padding: 20px 10px 20px 10px;position: relative;}
    .msg_type .id_avatar {float: left;width: 50px;height: 50px;position: relative }
    .msg_type .id_avatar .msg_notice_num {position: absolute;top: -5px;right: -5px}
    .msg_type .msg_main {margin-left: 65px;height: 50px;overflow: hidden; _width: 464px;}
    *+html .msg_type .msg_main { *height: 51px;}
    .msg_type .msg_main .msg_detail {line-height: 30px;white-space: nowrap;text-overflow: ellipsis;overflow: hidden;}
    .msg_type .msg_main .msg_title {margin-bottom: 5px;font-size: 14px;font-weight: bold;}
    .msg_type .msg_ctrls{position: absolute;right: 0;top: 22px;height: 20px;line-height: 20px;}
</style> 
<?php
 $this->pageTitle = "我的私信 - " .Yii::app()->name;
 ?>
<div class="content clearfix">
    <div class="main-content">
        <div class="content-left">
            <fieldset>
                <legend style="margin-bottom:10px;font-size:15px;"><b>我的私信</b><a class="btn  btn-info btn-mini <?php echo Yii::app()->user->isGuest ? 'noLogin' : 'CreteMessage'; ?>" style="margin-top:10px;margin-right:5px;float:right;" href="javascript::"><i class="icon-plus" style="margin-right:2px;"></i>写私信</a></legend>
            </fieldset>
            <div class="timeline-content">
                <div class="private_lists" id="msg_lists">
                    <?php echo $this->renderPartial('../_list', array('model' => $model), true, false); ?>
                </div>
            </div>
        </div>
        <div class="content-right alert alert-info">担心骚扰？可以 设置 为「只允许我关注的人给我发私信」。</div>
    </div>
</div>
<script>
    var createReplyUrl = '<?php echo $this->createUrl("createreply"); ?>';
    var deleteByUser = '<?php echo $this->createUrl("deletebyuser"); ?>';
</script>
<script>
    $(function(){
        $(".noLogin").click(function(e){
            $('#myModal').modal('show');
            $('#FastLogin').click();
            return false;
        });
        $(".CreteMessage").click(function(e){
            $('#messageModal').modal('show');
            $("#Message_user_name").val("").removeAttr("readonly");
            $("#Message_to_uid").val("");
            return false;
        });
        $("[name='delete']").click(function(){
            var self = $(this);
            var user_uid = self.attr("data-uid");
            if(window.confirm("你确定要删除与"+self.attr("data-username")+"的所有会话吗?")){  
                $.post(deleteByUser, {uid: user_uid}, function(data){
                    self.parents(".msg_type").slideUp('fast', function(){
                        $(this).remove();
                    });
                    if(parents.find(".msg_type").length == 0){
                        $("#msg_lists").append(' <div class="well well-small">暂无私信!</div> ');
                    }
                })
            }
            return false;
        });

    });
</script>