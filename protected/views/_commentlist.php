<style>
    .popover-style {
        position: relative;
        max-width:98% !important;
        width:98% !important;
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
    }

    .popover-style .comment-arrow,
    .popover-style .comment-arrow:after {
        position: absolute;
        display: block;
        width: 0;
        height: 0;
        border-color: transparent;
        border-style: solid;
    }

    .popover-style .comment-arrow {
        border-width: 11px;
    }

    .popover-style .comment-arrow:after {
        border-width: 10px;
        content: "";
    }


    .popover-style.right .comment-arrow {
        top: 50%;
        left: -11px;
        margin-top: -11px;
        border-right-color: #999;
        border-right-color: rgba(0, 0, 0, 0.25);
        border-left-width: 0;
    }

    .popover-style.right .comment-arrow:after {
        bottom: -10px;
        left: 1px;
        border-right-color: #ffffff;
        border-left-width: 0;
    }
    .comment-content,.comment-reply{
        margin: 5px 0;
        padding: 5px 0;
    }
</style>
<div class="list">
    <div class="list-comment">
        <div class="comment-list clearfix">
            <a name="<?php echo "diary_comment_" . $data->id; ?>"></a>
            <div class="item clearfix" style="margin-bottom: 10px;position: relative;"> 
                <div class="user" style="width:6%;float: left;">      
                    <div class="u-icon"> <a class="user-label" data-id="<?php echo $data->user_id;?>"   target="_blank" href="<?php echo $this->createUrl('default/userinfo', array('user_id' => $data->user_id)); ?>"> <img height="50" width="50"  src="<?php echo $this->createUrl("getimage",array("id"=>$data->user_id,"type"=>"avatar")); ?>" alt="<?php echo User::getNameById($data->user_id); ?>"> </a>      
                    </div>      
                </div>  
                <div class="clearfix box-section" style="width:94%;float: right;">
                    <div class="clearfix  right  popover-style" style="display:block;left:0;clear:both;right:0;position: relative;">
                        <div class="comment-arrow" style="top:20px;"></div>
                        <div class="popover-content">    
                            <div class="o-topic clearfix" style="border-bottom:1px solid #ccc;">          
                                  <span class="pull-left"><a class="user-label" data-id="<?php echo $data->user_id;?>" target="_blank" href="<?php echo $this->createUrl('default/userinfo', array('user_id' => $data->user_id)); ?>"><b><?php echo User::getNameById($data->user_id); ?></b></a></span><span class="pull-right"><?php echo Comment::timeintval($data->create_time); ?></span></div>
                            <p class="comment-content"><?php echo $data->content; ?></p>
                            <p class="comment-content clearfix"> 
                                <?php 
                                if((Yii::app()->user->name=="admin") || (($data->model == "comment") && ($data->pk_id==Yii::app()->user->id)) || Comment::model()->getIsCreateUser($data->pk_id,$data->model)){?>
                                <?php
                                $this->widget('bootstrap.widgets.TbButton', array(
                                    'label' => '删除',
                                    'size' => 'small',
                                    'icon' => 'icon-remove-2',
                                    'block' => true,
                                    'buttonType' => 'link',
                                    'url'=>'',
                                    'htmlOptions' => array('style' => 'width:70px;float:right;margin-left:10px;margin-top:0 !important;', 'data-name' => Yii::app()->user->isGuest ? 'noLogin' : 'delete-comment',"data-value"=>$data->id)
                                ));
                                ?>
                                 <?php }?>
                                 <?php  if(($data->model != "comment") || ($data->model == "comment" && $this->getAction()->getId()=="personal")){?>
                                <?php
                                $this->widget('bootstrap.widgets.TbButton', array(
                                    'label' => '回复',
                                    'size' => 'small',
                                    'icon' => 'icon-bubble-2',
                                    'block' => true,
                                    'htmlOptions' => array('style' => 'width:70px;float:right;margin-top:0 !important;', 'data-name' => Yii::app()->user->isGuest ? 'noLogin' : 'reply-comment', 'data-value' => $data->id, 'user-value' => $data->user_id, 'name-value' => User::getNameById($data->user_id), 'data-page' => $_GET["page"])
                                ));
                                ?>
                               <?php }?>
                            </p>
                           <?php echo Comment::replyList(Comment::model()->getComment($data->id,$data->model,$data->pk_id));?>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>