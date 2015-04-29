<style>
    .children-item {
        position: relative;
        float: left;
        width: 50%;
        height: 102px;
        list-style: none;
        border-bottom: 1px dotted #eee;
    }
    .children-item  .blk {
        padding-left: 62px;
        margin: 18px 15px 18px 0;
    }
    .children-item img {
        position: absolute;
        left: 0;
        width: 50px;
        height: 50px;
        border-radius: 3px;
    }
    .children-item .follow {
        position: absolute;
        top: 18px;
        right: 15px;
        color: #999;
    }
</style>
<div class="children-item clearfix">
    <div class="blk  clearfix">
        <a class="clearfix" target="_blank" href="<?php echo $this->createUrl("topic", array("id" => $data->id)); ?>" title="<?php echo $data->name; ?>">
            <img class="clearfix" src="<?php echo $this->createUrl("getimage", array("id" => $data->id, "type" => "topic")); ?>" alt="<?php echo $data->name; ?>">
            <strong><?php echo $data->name; ?></strong>
        </a>
        <p><?php echo  (strlen($data->desc) >34) ? mb_substr($data->desc, 0, 34, 'utf-8') . '...' : $data->desc; ?></p>
        <?php $attention = ((!in_array(Yii::app()->user->id, $data->join_user == "" ? array() : explode(",", trim($data->join_user, ","))) || Yii::app()->user->isGuest)) ? false : true; ?>
        <a name="<?php echo Yii::app()->user->isGuest ? 'noLogin' : 'joinTopic'; ?>" href="javascript:;" data-topicid="<?php echo $data->id; ?>" class="follow meta-children-item zg-follow"><?php echo $attention ? '取消关注' : '关注'; ?></a>
    </div>
</div>