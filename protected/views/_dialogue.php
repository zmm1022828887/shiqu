<style>
    .popover-style {
        position: relative;
        padding: 1px;
        text-align: left;
        white-space: normal;
        background-color: #ffffff;
        border: 1px solid #ccc;
        border: 1px solid rgba(0, 0, 0, 0.2);
        -webkit-border-radius: 6px;
        -moz-border-radius: 6px;
        border-radius: 6px;
    }

    .popover-style.right {
        margin-left: 10px;
    }

    .popover-style .popover-title {
        padding: 8px 14px;
        margin: 0;
        font-size: 14px;
        font-weight: normal;
        line-height: 18px;
        background-color: #f7f7f7;
        border-bottom: 1px solid #ebebeb;
        -webkit-border-radius: 5px 5px 0 0;
        -moz-border-radius: 5px 5px 0 0;
        border-radius: 5px 5px 0 0;
    }

    .popover-style .popover-title:empty {
        display: none;
    }

    .popover-style .popover-content {
        padding: 9px 14px;
        max-width: 600px;
    }

    .popover-style .arrow,
    .popover-style .arrow:after {
        position: absolute;
        display: block;
        width: 0;
        height: 0;
        border-color: transparent;
        border-style: solid;
    }

    .popover-style .arrow {
        border-width: 11px;
    }

    .popover-style .arrow:after {
        border-width: 10px;
        content: "";
    }


    .popover-style.right .arrow {
        top: 50%;
        left: -11px;
        margin-top: -11px;
        border-right-color: #999;
        border-right-color: rgba(0, 0, 0, 0.25);
        border-left-width: 0;
    }

    .popover-style.right .arrow:after {
        bottom: -10px;
        left: 1px;
        border-right-color: #ffffff;
        border-left-width: 0;
    }
    .popover-style.left .arrow {
        top: 50%;
        right: -11px;
        margin-top: -11px;
        border-left-color: #999;
        border-left-color: rgba(0, 0, 0, 0.25);
        border-right-width: 0;
    }

    .popover-style.left .arrow:after {
        right: 1px;
        bottom: -10px;
        border-left-color: #ffffff;
        border-right-width: 0;
    }
    .msg_icon_close {
        vertical-align: top;
        visibility: hidden;
        _visibility: visible;
        color: #797A81;
        height: 12px;
        width: 12px;
        line-height: 12px;
        font-size: 12px;
        text-align: center;
        text-decoration: none;
        border-radius: 2px;
        display: block;
        float: right;
        margin: 3px 5px 0 10px;
    }
    .talk-content{float: left;}
    .create-time{float: left;clear: both;}
    .dialogue .dialogue-talk:hover .msg_icon_close {
        visibility: visible;
        color: #FFFFFF;
        background-color: #49afcd;
    }
</style>
<?php
$delete_url = $this->createUrl('deletemessage');
$msgJs = <<<JS
        $(".talk-list").find("a[action-type='delMessage']").live ("click", function(e){
        var id = $(this).attr("action-data-id");
       var btn = $(this);
       if(window.confirm('确定要删除此对话？')){
        $.post('$delete_url', {id: id}, function(){
            btn.parents(".dialogue").slideUp('fast', function(){
                    $(this).remove();
                });
                    var old_total = $(".total").text();
                    var new_total = old_total - 1;
                     $(".total").text(new_total);
                      if(new_total == 0){
            $(".list-content").append('<div class="well well-small">你和ta之间还没有对话哦!</div>');
        }      
        })
        }else{
        return false;
        }
    });
 
JS;
Yii::app()->getClientScript()->registerScript('msgJs', $msgJs);
echo "<div style='padding-top:20px'>";
if (count($model) > 0) :
    foreach ($model as $v) :
        ?>
        <div class="dialogue" style="width:100%;">
            <div class="dialogue-talk">
                <div class="talk-list clearfix">
                    <div class="item clearfix" style="margin-bottom: 10px;position: relative;"> 
                        <div class="user" style="width:6%;float: <?php echo $v['is_me_send'] ? "right " : "left"; ?>;">      
                            <div class="u-icon"><a title="查看TA的个人信息" href="<?php echo $this->createUrl("userinfo", array("user_id" => $v['create_user'])); ?>" target="_blank"><img height="50" width="50" src="<?php echo $this->createUrl("getimage",array("id"=>$v['create_user'],"type"=>"avatar")); ?>"  alt="<?php echo User::getNameById($v['create_user']); ?>"> </a>      
                            </div>      
                        </div>  
                        <div class="clearfix box-section" style="float:<?php echo $v['is_me_send'] ? "right;margin-right:10px " : "left"; ?>">
                            <div class="clearfix  <?php echo $v['is_me_send'] ? "left" : "right"; ?>  popover-style">
                                <div class="arrow" style="top:20px;"></div>
                                <div class="popover-content"> 
                                    <a class="msg_icon_close icon-close-2" action-type="delMessage" action-data-id="<?php echo $v['id'] ?>" action-data-name="<?php echo $user[$v['create_user']]['user_name'] ?>" href="javascript:;" title="删除私信对话"></a>
                                    <p class="talk-content"> <?php echo $v['is_me_send'] ? "我" : User::model()->getNameById($v['create_user']); ?>
                                        ：&nbsp;<?php echo $v['content']; ?></p>
                                    <div class="create-time"><?php echo Comment::model()->timeintval($v['create_time']); ?></div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <div class="well well-small">你和ta之间还没有对话哦!</div>  
<?php endif; ?>
<?php echo "</div>"; ?>