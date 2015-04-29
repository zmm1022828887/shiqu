<style>
    .content{margin: 0 auto; width: 1030px; position: relative;padding-top: 30px;}
    .content legend{border-bottom:none;}
    .content .control-label{text-align: left;}
    .content .content-left{width: 690px;float: left;}
    .content .content-right{width: 320px; float: right;}
</style>  
<?php $this->pageTitle = "任务中心 - " . Yii::app()->name; ?>
<div class="content clearfix">
    <div class="content-left">
        <div class="control-group clearfix">
            <label class="control-label pull-left" style="width: 120px;">当前财富值：</label>
            <div class="controls pull-left"><span style="color:#ff0000;font-weight: bolder"><?php echo User::model()->findByPk(Yii::app()->user->id)->wealth;?></span></div>
             <label class="control-label pull-left" style="width: 120px;margin-left: 300px;">当前财富值排名：</label>
             <div class="controls pull-left">第 <span style="color:#ff0000;font-weight: bolder"><?php echo User::model()->getUserWealth(Yii::app()->user->id);?></span> 名</div>
        </div>
        <div class="control-group clearfix">
            <label class="control-label pull-left" style="width: 120px;">任务完成度：</label>
            <div class="controls pull-left">
                <?php
                $k = 0;
                $j = 0;
                if ($settingArray['topic_type'] == 0) {
                    $j++;
                    if (User::model()->getStatusLabel("topic", "value")) {
                        $k++;
                    }
                }
                if ($settingArray['question_type'] == 0) {
                    $j++;
                    if (User::model()->getStatusLabel("question", "value")) {
                        $k++;
                    }
                }
                if ($settingArray['answer_type'] == 0) {
                    $j++;
                    if (User::model()->getStatusLabel("answer", "value")) {
                        $k++;
                    }
                }
                if ($settingArray['article_type'] == 0) {
                    $j++;
                    if (User::model()->getStatusLabel("article", "value")) {
                        $k++;
                    }
                }
                $precent = round(($k / $j ) * 100);
                $this->widget('bootstrap.widgets.TbProgress', array(
                    // 'type'=>'success', // 'info', 'success' or 'danger'
                    'percent' => $precent,
                    'content' => $precent . "%",
                    'animated' => true,
                    'type' => 'info',
                    'htmlOptions' => array('style' => 'margin-bottom:6px;width:500px;')
                ));
                ?>
            </div>

        </div>
        <table class="items table table-striped table-bordered">
            <thead>
                <tr>
                    <th>任务名称</th><th>财富值奖励</th><th style='text-align: center;'>状态</th><th style='text-align: center;'>操作</th></tr>
            </thead>
            <tbody>

                <?php if ($settingArray['question_type'] == 0) { ?>
                    <tr><td>提出一个问题</td><td><span>财富值<em style='color:#ff0000'>+<?php echo $settingArray['question_score']; ?></em>点</span></td><td style="text-align: center;"><?php echo User::model()->getStatusLabel("question", "label"); ?></td><td style="text-align:center;" nowrap="1">
                            <?php if (User::model()->getStatusLabel("question", "value")) { ?>
                                <a class="td-link-icon text-info btn-do" title="问题" rel="tooltip" target="_blank" href="<?php echo $this->createUrl("allquestion"); ?>"><i class="icon-eye-2"></i>问题</a>
                            <?php } else { ?>                
                                <a class="td-link-icon text-info btn-do" title="提出问题" rel="tooltip" href="javascript:;" name="ask"><i class="icon-play-3"></i>提出问题</a>
                            <?php } ?>
                        </td></tr>
                <?php } ?>
                <?php if ($settingArray['answer_type'] == 0) { ?>
                    <tr><td>回答一个问题</td><td><span>财富值<em style='color:#ff0000'>+<?php echo $settingArray['answer_score']; ?></em>点</span></td><td style="text-align: center;"><?php echo User::model()->getStatusLabel("answer", "label"); ?></td><td style="text-align:center;" nowrap="1">
                            <?php if (User::model()->getStatusLabel("answer", "value")) { ?>
                                <a class="td-link-icon text-info btn-do" title="问题" rel="tooltip" target="_blank" href="<?php echo $this->createUrl("allquestion"); ?>"><i class="icon-eye-2"></i>问题</a>
                            <?php } else { ?>                
                                <a class="td-link-icon text-info btn-do" title="立即去完成" rel="tooltip" target="_blank" href="<?php echo $this->createUrl("allquestion"); ?>"><i class="icon-play-3"></i>立即去完成</a>
                            <?php } ?></td></tr>
                <?php } ?>
                <?php if ($settingArray['article_type'] == 0) { ?>
                    <tr><td>发表一篇文章</td><td><span>财富值<em style='color:#ff0000'>+<?php echo $settingArray['article_score']; ?></em>点</span></td><td style="text-align: center;"><?php echo User::model()->getStatusLabel("article", "label"); ?></td><td style="text-align:center;" nowrap="1"> <?php if (User::model()->getStatusLabel("article", "value")) { ?>
                                <a class="td-link-icon text-info btn-do" title="文章" rel="tooltip" target="_blank" href="<?php echo $this->createUrl("allarticle"); ?>"><i class="icon-eye-2"></i>文章</a>
                            <?php } else { ?>                
                                <a class="td-link-icon text-info btn-do" title="立即去完成" rel="tooltip" target="_blank" href="<?php echo $this->createUrl("createarticle"); ?>"><i class="icon-play-3"></i>立即去完成</a>
                            <?php } ?></td></tr>
                <?php } ?>
                <?php if ($settingArray['topic_type'] == 0) { ?>
                    <tr><td>新建一个话题</td><td><span>财富值<em style='color:#ff0000'>+<?php echo $settingArray['topic_score']; ?></em>点</span></td><td style="text-align: center;"><?php echo User::model()->getStatusLabel("topic", "label"); ?></td><td style="text-align:center;" nowrap="1"> <?php if (User::model()->getStatusLabel("topic", "value")) { ?>
                                <a class="td-link-icon text-info btn-do" title="查看小组" rel="tooltip" target="_blank" href="<?php echo $this->createUrl("mytopic"); ?>"><i class="icon-eye-2"></i>查看小组</a>
                            <?php } else { ?>                
                        <a class="td-link-icon text-info btn-do" title="立即去完成" rel="tooltip" herf="javascript;:" name="CreteTopic"><i class="icon-play-3"></i>立即去完成</a>
                            <?php } ?></td></tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <div class="content-right"><div class="alert alert-info">当你的 <a href="javascript:;"style="color:#ff0000">财富值</a> 越多，你获得的权限就越大，你可以 <a href="javascript:;" name="showWealth" style="color:#ff0000">查看财富值获取规则</a>。</div>
        <div id="setting-tabs" class="tabs-above">
            <fieldset>
                <legend>财富值排行榜</legend> 
            </fieldset>
            <?php
            $columns = array(
                array('header' => '排名', 'name' => 'id', 'value' => '', 'headerHtmlOptions' => array('style' => 'width:40px;'),'htmlOptions'=>array('class'=>"order")),
                array('header' => '用户名', 'type' => 'raw', 'value' => 'CHtml::openTag("img", array("src" => Yii::app()->controller->createUrl("getimage",array("id"=>$data["id"],"type"=>"avatar")),"style"=>"margin-right:4px;height:30px;width:30px;")).CHtml::link($data["user_name"],array("userinfo","user_id"=>$data["id"]),array("target"=>"_blank","title"=>"查看".$data["user_name"]."个人信息"))',),
                array('header' => '财富值', 'type' => 'raw','name' => 'wealth', 'value' => '$data["wealth"]', 'headerHtmlOptions' => array('style' => 'width:60px;')),
            );
            $this->widget('bootstrap.widgets.TbGridView', array(
                'id' => 'user-grid',
                'type' => 'striped',
                'template' => '{items}',
                'rowHtmlOptionsExpression' => 'array("class"=>"num".$data["order"])',
                'dataProvider' => $listDataProvider,
                'columns' => $columns
            ));
            ?>
        </div>
    </div>
</div>
<?php $this->beginWidget('bootstrap.widgets.TbModal', array('id' => 'wealthModal', 'options' => array("backdrop" => 'static'), 'fade' => false)); ?>
<div class="modal-header">
    <a class="close" data-dismiss="modal">×</a>
    <h4 class="modal-title" id="myModalLabel">财富值获取规则</h4> 
</div>
<div class="modal-body" style="max-height:500px;">
    <table class="items table table-striped table-bordered">
        <thead>
            <tr>
                <th>操作</th><th>财富值奖励</th></tr>
        </thead>
        <tbody>
            <tr><td>注册</td><td><span>财富值<em style='color:#ff0000'><?php echo $settingArray['registe_type'] == 0 ? "+" : "-"; ?><?php echo $settingArray['register_score']; ?></em>点</span></td></tr>
            <tr><td>登陆</td><td><span>财富值<em style='color:#ff0000'><?php echo $settingArray['login_type'] == 0 ? "+" : "-"; ?><?php echo $settingArray['login_score']; ?></em>点</span></td></tr>
            <tr><td>提出一个问题</td><td><span>财富值<em style='color:#ff0000'><?php echo $settingArray['question_type'] == 0 ? "+" : "-"; ?><?php echo $settingArray['question_score']; ?></em>点</span></td></tr>
            <tr><td>回答一个问题</td><td><span>财富值<em style='color:#ff0000'><?php echo $settingArray['answer_type'] == 0 ? "+" : "-"; ?><?php echo $settingArray['answer_score']; ?></em>点</span></td></tr>
            <tr><td>发表一篇文章</td><td><span>财富值<em style='color:#ff0000'><?php echo $settingArray['article_type'] == 0 ? "+" : "-"; ?><?php echo $settingArray['article_score']; ?></em>点</span></td></tr>
            <tr><td>创建一个话题</td><td><span>财富值<em style='color:#ff0000'><?php echo $settingArray['topic_type'] == 0 ? "+" : "-"; ?><?php echo $settingArray['topic_score']; ?></em>点</span></td></tr>
        </tbody>
    </table>
    <div><span class="label label-important">温馨提示：当日任务完成后再次进行此操作，财富值不会再增加。</span></div>

</div>
<?php $this->endWidget(); ?>