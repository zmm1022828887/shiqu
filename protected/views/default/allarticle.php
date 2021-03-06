<style>
    .content{margin: 0 auto; width: 1030px; }
    .content .content-left{width:720px;position: relative; display: inline-block;} 
    .content .sidebar{width:260px; border-width: 2px; border-style: solid;border-top: 0;background-color: #fff; display: inline-block;height: 260px;background-color: #FAFAFA;}
    .content .pic{display: inline-block; position: absolute; left: 210px; top:0;padding: 10px;}
    .content .news{display: inline-block; width: 260px; float: right;}
    .content .news .product-section{padding-bottom: 20px;}
    .content .news .small-comment{height: 20px;white-space:nowrap;}
    .content .news .grid-view{padding-top: 0;}
    .content .all-category{ width: 900px;padding-top: 20px;}
    .content .all-category .title{height:30px;line-height: 30px;border-bottom:1px solid #ccc;width:760px;}
    .content .all-category .title .more{padding-left:20px;display: inline-block;float: right;}
    .main-title {
        margin: 0;
        padding: 20px 0 20px;
        font-size: 18px;
        vertical-align: middle;
    }
</style>
<?php
$this->pageTitle = "文章 - " . Yii::app()->name;
$this->widget('bootstrap.widgets.TbBreadcrumbs', array('links' =>  array("文章")));
?>
<div class="content clearfix">
    <div class="content-left">
        <p class="main-title">
            你有什么突发奇想？<a href="<?php echo Yii::app()->user->isGuest ? 'javascript:;' : $this->createUrl("createarticle"); ?>" class="btn btn-primary" name="<?php echo Yii::app()->user->isGuest ? 'noLogin' : 'article'; ?>">撰写文章</a>
        </p>
        <?php
        if (!isset($_GET["action"])) {
            if (count($dataProvider->getdata()) == 0) {
                echo "<div class='alert alert-info'>暂无文章．</div>";
            } else {
                $this->widget('bootstrap.widgets.Tbtabs', array(
                    'type' => 'tabs', // '', 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
                    'htmlOptions' => array('style' => 'margin-top:10px;', 'class' => 'vote-tabs'),
                    'tabs' => array(
                        array('label' => '最新的', 'content' => $this->renderPartial('../_articletabs', array("type" => "new", "dataProvider" => $dataProvider), true), 'active' => true),
                        array('label' => '热门的', 'content' => $this->renderPartial('../_articletabs', array("type" => "hot", "dataProvider" => $dataProvider), true), true),
                    )
                        )
                );
            }
        }
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
                $hotTopicModel = Topic::getTopicOrder("aricle");
                if (!empty($hotTopicModel)) {
                    foreach ($hotTopicModel as $key => $value) {
                        ?>
                        <div class="clearfix" style="padding:4px 0;"><a class="topic-label" style="margin-right:4px;" data-id="<?php echo $value['id']; ?>" href="javascript:;"><span class="badge"><?php echo $value['name']; ?></span></a><span style="color:#ccc;margin-left:0;">(共<?php echo $value['count']; ?>篇文章)</span></div>
                        <?php
                    }
                } else {
                    echo "<div class='alert alert-info'>暂无热门话题</div>";
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
                        <div class="clearfix" style="padding:4px 0;"><a class="topic-label" style="margin-right:4px;" data-id="<?php echo $newTopic->id; ?>" href="javascript:;" style="margin-right:4px;"><span class="badge"><?php echo $newTopic->name; ?></span></a><span style="color:#ccc;margin-left:0;">(共<?php echo Article::model()->count("publish=1 and topic_ids like '%," . $newTopic->id . ",%'"); ?>个问题)</span></div>
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