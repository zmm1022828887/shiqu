<style>
    .popover-style {
        position: relative;
        max-width:96% !important;
        width:96% !important;
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
</style>
<div class="product">
    <div class="product-comment">
        <div class="comment-list clearfix">
            <a name="<?php echo "comment_".$data->id;?>"></a>
            <div class="item clearfix" style="margin-bottom: 10px;position: relative;"> 
                <div class="user" style="width:6%;float: left;">      
                    <div class="u-icon"> <a  title="查看Ta个人信息" target="_blank" href="<?php echo $this->createUrl('default/userinfo', array('user_id' => $data->user_id));  ?>"> <img height="50" width="50" src="<?php echo User::getAvatarById($data->user_id);?>"  alt="<?php echo User::getNameById($data->user_id); ?>"> </a>      
                    </div>      
                    <div class="u-name"><a target="_blank" href="<?php echo $this->createUrl('default/userinfo', array('user_id' => $data->user_id)); ?>"><b><?php echo User::getNameById($data->user_id); ?></b></a></div>
                </div>  
                <div class="clearfix box-section" style="width:94%;float: right;">
                    <div class="clearfix  right  popover-style" style="display:block;left:0;clear:both;right:0;position: relativ">
                        <div class="arrow" style="top:20px;"></div>
                        <div class="popover-content">    
                            <div class="o-topic clearfix" style="border-bottom:1px solid #ccc;">          
                                <span class="pull-left star-pic sa<?php echo $data->score; ?>"></span>
                                <span class="pull-right"><?php echo date('Y-m-d H:i:s', $data->create_time); ?></span></div>
                            <?php if ($data->tags != ""): ?>
                                <p class="comment-tags" style="padding-top:10px;"><span class="comment-label"><b>标签：</b></span>
                                    <?php
                                    $tags = array();
                                    $tags_array = explode(",", $data->tags);
                                    for ($i = 0; $i < count($tags_array); $i++) {
                                        echo "<span class='label label-warning' style='margin-right:10px;'>" . Tags::model()->getNameById($tags_array[$i]) . "</span>";
                                    }
                                    ?>
                                </p>
                            <?php endif; ?>
                            <p class="comment-content"><span class="comment-label"><b>评论：</b></span><?php echo $data->content; ?></p>
                            <p class="comment-content clearfix"> 
                                <span style="display:inline-block;float: left;">此评价对我是否有用
                                    <?php
                                    $this->widget('bootstrap.widgets.TbButton', array(
                                        'label' => '是(' . Comment::getCountAgreeByCommentId($data->id) . ')',
                                        'size' => 'small',
                                        'block' => true,
                                        'htmlOptions' => array('style' => 'width:50px;display:inline-block;', 'data-name' => Yii::app()->user->isGuest ? 'noLogin' : 'agree', 'data-value' => $data->id)
                                    ));
                                    ?></span><?php
                                    $this->widget('bootstrap.widgets.TbButton', array(
                                        'label' => '回复',
                                        'size' => 'small',
                                        'block' => true,
                                        'htmlOptions' => array('style' => 'width:50px;float:right;', 'data-name' => Yii::app()->user->isGuest ? 'noLogin' : 'reply-comment', 'data-value' => $data->id, 'user-value' => $data->user_id, 'name-value' => User::getNameById($data->user_id),'data-page'=>$_GET["page"])
                                    ));
                                    ?></p>
                                <?php
                                $model = CommentReply::model()->findAll("comment_id=:id", array(":id" => $data->id), array("order" => "create_time"));
                                foreach ($model as $key => $reply) {
                                    ?>
                                <div class="product-comment-reply clearfix" style="border-top:1px dashed #ccc;padding-left:10px;">
                                    <span><b><a href="<?php echo $this->createUrl('default/userinfo', array('user_id' => $reply->reply_user_id)); ?>"   target="_blank" title="<?php echo User::getNameById($reply->reply_user_id); ?>"><?php echo User::getNameById($reply->reply_user_id); ?></a></b> @ <b><a href="<?php echo $this->createUrl('default/userinfo', array('user_id' => $reply->user_id)); ?>"  target="_blank" title="<?php echo User::getNameById($reply->user_id); ?>"><?php echo User::getNameById($reply->user_id); ?></a></b> <b> : </b> <?php echo $reply->content; ?></span><br>
                                    <i><?php echo date('Y-m-d H:i:s', $reply->create_time); ?></i>
                                    <a user-value="<?php echo $reply->reply_user_id; ?>" data-value="<?php echo $reply->comment_id; ?>"  data-page="<?php echo $_GET["page"]; ?>" name-value="<?php echo User::getNameById($reply->reply_user_id); ?>" data-name="<?php echo Yii::app()->user->isGuest ? 'noLogin' : 'reply-comment'; ?>" style="float:right;display: none;">回复</a>
    <!--                                                                   <a user-value="<?php echo $reply->reply_user_id; ?>" data-value="<?php echo $reply->comment_id; ?>"  name-value="<?php echo User::getNameById($reply->reply_user_id); ?>" data-name="reply-comment" style="float:right" class="btn btn-small">回复</a>-->
                                </div>
                                <?php
                            }
                            ?>  

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>