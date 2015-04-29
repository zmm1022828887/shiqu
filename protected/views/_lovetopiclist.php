<style>

</style>
<div class="topic-item" id="<?php echo $data->id;?>">
    <?php $model = Topic::model()->findByPk($data->topic_id); ?>
    <a href="<?php echo $this->createUrl("topic", array("id" => $model->id)); ?>" title="<?php echo $model->name; ?>" class="topic-link-image"><img src="<?php echo $this->createUrl("getimage", array("id" => $model->id, "type" => "topic")); ?>" height="40p" width="40"></a>
    <span class="side-topic-item ellipsis">       
        <a href="<?php echo $this->createUrl("topic", array("id" => $model->id)); ?>" class="topic-link side-topic-title" title="<?php echo $model->name; ?>"><?php echo $model->name; ?></a>        
        <span class="side-topic-meta clearfix">
            <?php $count = Topic::model()->count("parent_id=" . $data->topic_id); ?>
            <?php if ($count > 0) { ?>
                <span class="zg-gray"><?php echo $count; ?> 个子话题 •</span>
            <?php } ?>
            <span class="zg-gray"><?php echo $model->join_user == "" ? 0 : count(explode(",", trim($model->join_user, ","))); ?> 人关注</span>
        </span>
    </span>
    <a href="javascript:;" data-action="delete" data-topicid="<?php echo $model->id;?>" class="delete" rel="tooltip" title="删除"><i class="icon-close-2"></i></a>
    <a href="javascript:;" data-action="up"  data-topicid="<?php echo $model->id;?>" class="up" rel="tooltip" title="置顶"><i class="icon-arrow-up-3"></i></a>
</div>