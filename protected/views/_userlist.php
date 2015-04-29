<div class="user-list">
    <div class="pic" style="display:inline-block;height: 70px;width: 70px;position: relative;">
        <a   style="color:#fff;text-align: center;" class="user-label"  data-id="<?php echo $data->from_user;?>" target="_blank" href="<?php echo $this->createUrl('default/userinfo', array('user_id' => $data->from_user)); ?>">
            <img height="70" width="70"  style="border-radius: 3px;" src="<?php echo $this->createUrl("getimage",array("id"=>$data->from_user,"type"=>"avatar")); ?>" alt="<?php echo User::getNameById($data->from_user); ?>">
            <span class="user-name"><?php echo User::getNameById($data->from_user); ?></span>
        </a>
       <?php if(($this->getAction()->getId() == "personal") || (($this->getAction()->getId() == "userinfo") && ($data->from_user == Yii::app()->user->id))) {?>
        <a class="remove-visit icon-close-2" action-type="delVisit" action-data-id="<?php echo $data->id;?>"  href="javascript:;" title="删除"></a>
       <?php }?>
    </div>
    <div class="name">
        <a  title="<?php echo User::getNameById($data->from_user) . " - " . $this->title; ?>" target="_blank" href="<?php echo $this->createUrl('default/userinfo', array('user_id' => $data->from_user)); ?>"><?php echo Comment::timeintval($data->create_time,'n月j日'); ?></a>
    </div>
</div>