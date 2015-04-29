<style>
    .profile-section-item{ border-top: 1px dotted #EEE;position: relative;padding: 12px 0;}
    .item-img-avatar{ float: left;height: 50px;margin: 2px 10px 0 0;width: 50px;border: 0 none;border-radius: 2px;}
    .list-content-title{font-weight: 700;margin: 0;line-height: 20px;font-size: 14px;outline: 0;}
    .big-gray,.big-gray a{  color: #999;font-size: 14px;font-weight: 400;}
    .details{color: #999;font-size: 12px;font-weight: 400;}
    .details .link-gray-normal{color: #999;font-weight: 400;}
    .more .dropdown-menu{min-width: 80px;}
</style>
<div class="list-view">
    <?php
    $criteria = new CDbCriteria;
    if ($this->getAction()->getId() == "query")
        $criteria->addSearchCondition('name', trim($_GET['q']));
    else{
        if(isset($_GET["type"]) && ($_GET["type"]=="admingroup")){
             $criteria->addCondition('create_user='.trim($_GET['user_id']));
        }else{
           $criteria->addSearchCondition('join_user', ",".trim($_GET['user_id']).",");
        }
    }
    $topicModel = Topic::model()->findAll($criteria);
    if (empty($topicModel)) {
        echo "<div class='alert alert-info'>没有找到相关的话题.</div>";
    } else {
        foreach ($topicModel as $topic) {
            ?>
            <div class="profile-section-item">
                <div style="float: right;">
                    <button  class="btn btn-smal btn-success  btn-small"  name="<?php echo Yii::app()->user->isGuest ? 'noLogin' : 'joinTopic'; ?>" data-topicid="<?php echo $topic->id; ?>" style="width: 78px;"><?php echo (!in_array(Yii::app()->user->id, explode(",", $topic->join_user)) || Yii::app()->user->isGuest) ? "关注" : "取消关注"; ?></button>
                </div>  <a title="<?php echo $topic->name; ?>"  href="<?php echo $this->createUrl("/default/topic", array("id" => $topic->id)); ?>">
                    <img src="<?php echo $this->createUrl("/default/getimage", array("id" => $topic->id,"type"=>"topic"));  ?>" class="item-img-avatar">
                </a>
                <div class="zm-list-content-medium">
                    <h2 class="list-content-title"><a  href="<?php echo $this->createUrl("/default/topic", array("id" => $topic->id)); ?>" class="zg-link" title="<?php echo $topic->name; ?>"><?php echo $topic->name; ?></a></h2>
                    <div class="big-gray" style="padding-left: 60px;"><?php echo $topic->desc; ?></div>
                    <div class="big-gray" style="padding-left:60px;"><a  href="<?php echo $this->createUrl("/default/topic", array("id" => $topic->id,"type"=>"user")); ?>">关注者 <?php echo $topic->join_user == "" ? 0 : count(explode(",", trim($topic->join_user, ","))); ?> 人</a></div>
                </div>
            </div>
        <?php }
    } ?>
</div>