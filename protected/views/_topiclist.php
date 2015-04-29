<style>
    ul,li{padding:0; margin:0;}
    .topic-list{margin:0 0 10px 0;}
    .topic-list a{ text-decoration: none; cursor: pointer;}
    .topic-list h1{margin:0 0 4px 0; background:none;  font-size:16px;overflow:hidden;height: 24px;padding-top: 8px;line-height: 24px;}  
    .topic-list h1 a{color: #000;}
    .topic-list h1 .time{display: inline-block; font-size: 14px; font-weight: normal;display: none;cursor: pointer;color:#ccc;} 
    .topic-list .topic-header-left{ float: left; text-align: center;height: 60px; width: 60px;margin:10px;margin-left: 0;}
    .topic-list .topic-header-right{margin-left: 70px;}
    .topic-list .about-info .tag{margin-left:5px;}
    .topic-list .about-info .tag a{display: inline-block;padding:0 5px;color:#fff; }
    .topic-list .topic-icon{color: #ccc;margin-left: 14px;}
    .question-body{height: 24px;}
</style>
<div class="view">
    <div class="topic-list">
        <div class="topic-header clearfix">
            <div class="topic-header-left">
                <a data-id="<?php echo $data->id; ?>" class="topic-label" href="javascript:;"><img height="50" width="50" src="<?php echo $this->createUrl("getimage", array("id" => $data->id, "type" => "topic")); ?>"  alt="<?php echo $data->name; ?>"></a>
            </div>
            <div class="topic-header-right">
                <?php $count = LoveTopic::model()->count("create_user=:create_user and topic_id=:topic_id", array(":create_user" => Yii::app()->user->id, ":topic_id" => $data->id)) ?>
                <h1> <?php echo CHtml::link($data->name, $this->createUrl("topic", array("id" => $data->id)), array("title" => $data->name)); ?><a class="time topic-icon" data-topicid="<?php echo $data->id; ?>" name="loveTopic" title="<?php echo $count > 0 ? "取消固定" : "固定话题"; ?>" href="javascrpit:;"><i class="icon-pushpin"></i><?php echo $count > 0 ? "取消固定" : "固定话题"; ?></a><span class="time pull-right" name="joinTopic" data-topicid="<?php echo $data->id; ?>">取消关注</span></h1>                
                <div class="about-info">
                    <?php
                    $criteria = new CDbCriteria;
                    $criteria->limit = 5;
                    $criteria->order = "create_time desc";
                    $criteria->addSearchCondition("topic_ids", "," . $data->id . ",");
                    $questionModel = Question::model()->findAll($criteria);
                    if (count($questionModel) > 0) {
                        foreach ($questionModel as $key => $value) {
                            ?>
                            <div class="question-body"><a title="<?php echo $value->title; ?>"  href="<?php echo $this->createUrl("question", array('id' => $value->id)); ?>"><?php echo $value->title; ?></a><span style="margin-left:10px;color:#ccc;"><?php echo Comment::timeintval($value->create_time); ?>,<?php echo ($value->answer_id != 0) ? "已解决" : "待解决"; ?></span></div>
                            <?php
                        }
                    } else {
                        echo "暂无问题";
                    }
                    ?>
                </div>
            </div>
        </div>
        <hr style="border-style: dotted ">
    </div>
</div>