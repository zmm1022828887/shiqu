<?php 
    if(count($model) > 0){

    foreach($model as $v){ 
?>
<div class="msg_type">
        <div class="private_list T_linecolor clearfix"  name="list">
            <div class="id_avatar">
                <a target="_blank" href="<?php echo $this->createUrl("userinfo",array("user_id"=> $v['user_id']));?>" data-id="<?php echo $v['user_id'];?>" class="user-label"><img src="<?php echo $v['user_avatar']?>" width="50" height="50"></a>                                    </div>
            <div class="msg_main">
                <div class="msg_title">
                <?php echo ($v['msg_me_send'] ? "我发送给 " : "").CHtml::link($v['user_name'],array("userinfo","user_id"=> $v['user_id']),array("class"=>"user-label","data-id"=> $v['user_id'],"target"=>"_blank")); ?> ：
                </div>
                <div class="msg_detail T_textb">
                    <?php echo $v['msg_content']?> 
                    <a href="javascript:;" class="msg_reply" action-type="send_private_msg" action-data-name="<?php echo $v['user_name']?>" action-data-id="<?php echo $v['user_id']?>"></a>
                </div>
            </div>
            <div class="msg_ctrls1"     style="position: absolute;
    right: 0;
    top: 22px;
    height: 20px;
    line-height: 20px;">
                <span class="T_textb"><?php echo Comment::timeintval($v['msg_create_time']); ?></span>  
            </div>
            <div class="message-opt">
                <ul>
                    <li><a href="<?php echo $v['msg_list_url'];?>">共<?php echo $v['msg_count'];?>对话</a></li>
                    <li>|</li>
                    <li><a href="javascript:;" name="reply" data-uid="<?php echo $v['user_id']?>" data-username="<?php echo $v['user_name']?>">回复</a></li> 
                    <li>|</li>
                    <li><a href="javascript:;" name="delete" data-uid="<?php echo $v['user_id']?>" data-username="<?php echo $v['user_name']?>">删除</a></li>
                </ul>
            </div>
        </div>
    </div>
<?php  }}else{ ?>
   <div class="well well-small">暂无私信!</div>  
<?php } ?>