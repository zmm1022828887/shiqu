<style>
    .content{margin: 0 auto; width: 1030px; }
    .content .content-left{width:720px;position: relative; display: inline-block;} 
    .content .sidebar{width:260px; border-width: 2px; border-style: solid;border-top: 0;background-color: #fff; display: inline-block;height: 260px;background-color: #FAFAFA;}
    .content .pic{display: inline-block; position: absolute; left: 210px; top:0;padding: 10px;}
    .content .news{display: inline-block; width: 260px; float: right;}
    .content .news .product-section{padding-bottom: 20px;}
    .content .news .small-comment{height: 20px;white-space:nowrap;}
    .content .news .grid-view{padding-top: 0;}
    .breadcrumbs{width: 1030px; margin: 0 auto;padding-top: 10px;}
    .main-title {
        margin: 0;
        padding: 20px 0 20px;
        font-size: 18px;
        vertical-align: middle;
    }
</style>

<div class="content clearfix">
    <div class="content-left">
        <div class="breadcrumbs">
            <?php
            $this->widget('zii.widgets.CBreadcrumbs', array(
            'links' => array(
            "文章" => array("allarticle"),
            "撰写文章"
            )));
            ?><!-- breadcrumbs -->
        </div>
        <?php $this->renderPartial('../_articleform', array("model" => $model)); ?>
    </div>
    <div class="news" style="padding-top:20px;">
        <div id="setting-tabs" class="tabs-above">
            <fieldset>
                <legend style="margin-bottom: 10px;">你撰写的文章</legend> 
            </fieldset>
            <?php
            $criteria = new CDbCriteria;
            $criteria->order = "update_time desc";
            $criteria->addCondition("create_user=" . Yii::app()->user->id);
            $criteria->addCondition("publish=1");
            $articleModel = Article::model()->findAll($criteria);
            $count = array();
            if (!empty($articleModel)) {
                echo '<table><tbody>';
                foreach ($articleModel as $article) {
                    $j++;
                    if ($j == 11) {
                        break;
                    }
                    ?>
                    <tr><td style="padding-left:0px;"><a  href="<?php echo $this->createUrl("article", array("id" => $article['id'])); ?>" title="<?php echo $article['subject']; ?>"><?php echo (strlen(strip_tags($answer['content'])) > 70) ? mb_strcut(strip_tags($article['subject']), 0, 70, 'utf-8') . "..." : $article['subject']; ?></a><span style="color:#ccc;margin-left:0;">(共<?php echo Comment::model()->count("pk_id = :pk_id and parent_id=0 and model='article'", array(":pk_id" => $article['id'])); ?>条评论)</span></td></tr>
                                <?php
                            }
                            echo '</tbody></table>';
                        } else {
                            echo "<div class='alert alert-info'>暂无其他文章</div>";
                        }
                        ?>
        </div>
    </div>
</div>