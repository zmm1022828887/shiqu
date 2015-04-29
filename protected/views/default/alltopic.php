<style>
    .content{margin: 0 auto; width: 1030px; }
    .content .content-left{width:740px;position: relative; display: inline-block;} 
    .content .sidebar{width:260px; border-width: 2px; border-style: solid;border-top: 0;background-color: #fff; display: inline-block;height: 260px;background-color: #FAFAFA;}
    .content .pic{display: inline-block; position: absolute; left: 210px; top:0;padding: 10px;}
    .content .news{display: inline-block; width: 260px; float: right;}
    .content .news .product-section{padding-bottom: 20px;}
    .content .news .small-comment{height: 20px;white-space:nowrap;}
    .content .news .grid-view{padding-top: 0;}
    .content .all-category{ width: 900px;padding-top: 20px;}
    .content .all-category .title{height:30px;line-height: 30px;border-bottom:1px solid #ccc;width:760px;}
    .content .all-category .title .more{padding-left:20px;display: inline-block;float: right;}
    .hot-category-list{  border-bottom: 1px dotted #ccc;padding-top: 10px; padding-bottom: 10px;}
    .hot-category-list .label{height: 20px; line-height: 20px;}
    .hot-category-list .l-title{ float: left;width: 60px;text-align: right;}
    .hot-category-list .l-right{ float: left; width: 1030px;}
    .hot-category-list .l-right li{display: inline-block;}
    .hot-category-list  a{color:#fff;}
    .hot-category-list  .close-label{color:#fff;}
    .hot-category-list  .close-label  a{color:#F9F9F9; display:inline-block;padding-left: 4px;}
</style>
<?php
$this->pageTitle = "话题 - " . Yii::app()->name;
$this->widget('bootstrap.widgets.TbBreadcrumbs', array('links' =>  array("话题")));
?>
<div class="content clearfix">
    <div class="content-left">
        <fieldset>
            <legend>话题广场   <?php if (!Yii::app()->user->isGuest) { ?><span class="pull-right" style="font-size: 14px;"><a href="<?php echo $this->createUrl("personal", array("type" => "jointopic", "user_id" => Yii::app()->user->id)); ?>">共关注了<?php echo Topic::model()->getAttendtionCount(Yii::app()->user->id); ?>个话题</a></span><?php } ?></legend>
        </fieldset>
        <?php if ($model == NULL) { ?>

            <div class="alert alert-info">暂无话题</div>
        <?php } else { ?>
            <div style="padding: 10px 0;border-bottom: 1px solid #eee;line-height: 30px;">
                <?php foreach ($topicModel as $key => $value) { ?>
                    <a style="margin-bottom:10px;" href="<?php echo $this->createUrl("alltopic", array("id" => $value->id)); ?>" title="<?php echo $value->name; ?>"><span class="badge  <?php echo ((isset($_GET["id"]) && $_GET["id"] == $value->id) || (!isset($_GET["id"]) && ($value->id == $model->id))) ? "badge-info" : ""; ?>"><?php echo $value->name; ?></span></a>
                <?php } ?>
            </div>
        <?php } ?>
        <?php
        $this->widget('bootstrap.widgets.TbListView', array(
            'dataProvider' => $topicDataProvider,
            'itemView' => '../_childrentopiclist',
            'emptyText' => '<div class="alert alert-info">暂无固定的话题</div>',
            'template' => '{items}{pager}',
            'id' => 'topic-item',
            'htmlOptions' => array('style' => 'padding-top:0px;')
        ));
        ?>
    </div>
    <div class="news" style="padding-top:20px;">
        <div id="setting-tabs" class="tabs-above">
            <?php if (Yii::app()->user->isGuest) { ?>
                <div class="alert alert-info"><?php echo Sys::model()->find()->site_desc; ?></div>
            <?php } ?>
            <fieldset style="margin-bottom:10px;">
                <legend style="margin-bottom: 10px;">热门话题</legend> 

                <?php
                $i = 0;
                $hotTopicModel = Topic::getTopicOrder();
                if (!empty($hotTopicModel)) {
                    foreach ($hotTopicModel as $key => $value) {
                        ?>
                        <div class="clearfix" style="padding:4px 0;"><a class="topic-label" style="margin-right:4px;" data-id="<?php echo $value['id']; ?>" href="javascript:;"><span class="badge"><?php echo $value['name']; ?></span></a><span style="color:#ccc;margin-left:0;">(共<?php echo $value['count']; ?>个问题)</span></div>
                        <?php
                    }
                } else {
                    echo "<div class='alert alert-info'>暂无最新话题</div>";
                }
                ?>
            </fieldset>
            <fieldset>
                <legend style="margin-bottom: 10px;">最新话题</legend> 
                <?php
                $criteria = new CDbCriteria;
                $criteria->order = "create_time  desc";
                $criteria->limit = 10;
                $newTopicModel = Topic::model()->findAll($criteria);
                $count = array();
                if (!empty($newTopicModel)) {
                    foreach ($newTopicModel as $newTopic) {
                        ?>
                        <div class="clearfix" style="padding:4px 0;"><a class="topic-label" style="margin-right:4px;" data-id="<?php echo $newTopic->id; ?>" href="javascript:;" style="margin-right:4px;"><span class="badge"><?php echo $newTopic->name; ?></span></a><span style="color:#ccc;margin-left:0;">(共<?php echo Question::model()->count("topic_ids like '%," . $newTopic->id . ",%'"); ?>个问题)</span></div>
                                <?php
                            }
                        } else {
                            echo "<div class='alert alert-info'>暂无最新话题</div>";
                        }
                        ?>
            </fieldset>
        </div>
    </div>
</div>