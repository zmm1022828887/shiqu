<style>
    .content{margin: 0 auto; width: 1030px; position: relative;padding-top: 30px;}
    .content .content-left{width:720px;position: relative; display: inline-block;} 
    .content .content-right{display: inline-block; width: 280px; float: right;}
    .topic-item{
        border-radius: 4px;
        border: 1px solid #DDD;
        padding: 10px;
        background: #fff;
        position: relative;
        margin-top: 15px;
    }
    .topic-item .topic-link-image{float:left}
    .side-topic-item{margin-left: 50px;display: block;}
    .side-topic-title{font-weight: 700;}
    .topic-item .delete,.topic-item .up{
        position: absolute;
        right: 10px;
        top: 10px;
        display: none;
        border-radius: 3px;
        width: 15px;
        line-height: 15px;
        text-align: center;
        color: #fff;
        background-color: #259;
        text-decoration: none;
        height: 15px;
    }
    .topic-item .delete{right: 30px;}
    .content-right > .nav-list > li > a {padding:6px;margin: 0;}
</style>  

<?php
$this->pageTitle = "我关注的话题 - " . Yii::app()->name;
?>
<div class="content clearfix">
    <div class="content-left">
        <?php if (!isset($_GET["type"])) { ?>
            <?php foreach (DesktopSetting::getArray(l) as $key => $value) {
                ?>  <div class="panel panel-default">
                    <div class="panel-heading">最新<?php echo $value->app_name; ?><a class="pull-right" title="全部<?php echo $value->app_name; ?>" href="<?php echo $this->createUrl("all" . $value->app_id); ?>">全部<?php echo $value->app_name; ?></a></div>
                    <div class="panel-body">
                        <?php $this->renderPartial('../templates/_' . $value->app_id . 'grid', array('limit' => $value->app_length)); ?>
                    </div>
                </div>
            <?php } ?>

            <?php
        } else if ($_GET["type"] == "article") {
            $criteria = new CDbCriteria;
            $criteria->addCondition("create_user = :create_user and publish=0");
            $criteria->params[':create_user'] = Yii::app()->user->id;
            $draftCount = Article::model()->count($criteria);
            ?>
            <fieldset>
                <legend>我的草稿（<?php echo $draftCount ?>）</legend>
            </fieldset>
            <?php
            if ($draftCount > 0) {
                $this->renderPartial('../_articlegrid');
            } else {
                echo '<div class="alert alert-info">暂无草稿</div>';
            }
            ?>
            <?php
        } else if ($_GET["type"] == "attentiontopic") {
            $criteria = new CDbCriteria;
            $criteria->limit = 10;
            $userId = Yii::app()->user->id;
            $criteria->addSearchCondition("join_user", "," . $userId . ",");
            $dataProvider = new CActiveDataProvider('Topic', array(
                'criteria' => $criteria,
                'sort' => array(
                    'defaultOrder' => 'create_time desc'
                )
            ));
            ?>
            <fieldset>
                <legend>我关注的话题（<?php echo $dataProvider->itemCount; ?>）</legend>
            </fieldset>
            <?php
            $this->widget('bootstrap.widgets.TbListView', array(
                'dataProvider' => $dataProvider,
                'itemView' => '../_topiclist',
                'emptyText' => '<div class="alert alert-info">暂无关注的话题</div>',
                'template' => '{items}{pager}',
                'id' => 'topic-list',
                'htmlOptions' => array('style' => 'padding-top:0px')
            ));
            ?>
            <?php
        } else if ($_GET["type"] == "lovetopic") {
            $model = new LoveTopic();
            $model->create_user = Yii::app()->user->id;
            $topicDataProvider = $model->search();
            ?>
            <fieldset>
                <legend>我常去的话题（<?php echo $topicDataProvider->itemCount; ?>）</legend>
            </fieldset>
            <?php
            $this->widget('bootstrap.widgets.TbListView', array(
                'dataProvider' => $topicDataProvider,
                'itemView' => '../_lovetopiclist',
                'emptyText' => '<div class="alert alert-info">暂无关注的话题</div>',
                'template' => '{items}{pager}',
                'id' => 'topic-item',
                'htmlOptions' => array('style' => 'padding-top:0px')
            ));
            ?>
            <?php
        } else if (($_GET["type"] == "help") || ($_GET["type"] == "forhelp")) {
            $requestModel = new Request();
            if ($_GET["type"] == "help") {
                $requestModel->delete_flag != 1;
                $requestModel->to_user = Yii::app()->user->id;
            } else {
                $requestModel->delete_flag != 2;
                $requestModel->create_user = Yii::app()->user->id;
            }
            $requestDataProvider = $requestModel->search();
            ?>
            <fieldset>
                <legend><?php echo $_GET["type"] == "help" ? "邀请我" : "我邀请"; ?>回答的问题（<span id="request-count"><?php echo $requestDataProvider->itemCount; ?></span>）
                    <?php
                    $this->widget('bootstrap.widgets.TbButtonGroup', array(
                        'toggle' => 'radio',
                        'size' => 'mini',
                        'buttons' => array(
                            array(
                                'label' => '全部',
                                'buttonType' => 'link',
                                'active' => !isset($_GET['reply']) ? true : false,
                                'htmlOptions' => array('title' => '全部'),
                                'url' => $this->createUrl('index', array("type" => $_GET["type"])),
                            ),
                            array(
                                'label' => '已回答',
                                'buttonType' => 'link',
                                'active' => (isset($_GET['reply']) && ($_GET['reply'] == 1)) ? true : false,
                                'url' => $this->createUrl('index', array("type" => $_GET["type"], "reply" => 1)),
                                'htmlOptions' => array('title' => '未回答'),
                            ),
                            array(
                                'label' => '未回答',
                                'buttonType' => 'link',
                                'active' => (isset($_GET['reply']) && ($_GET['reply'] == 0)) ? true : false,
                                'url' => $this->createUrl('index', array("type" => $_GET["type"], "reply" => 0)),
                                'htmlOptions' => array('title' => '未回答'),
                            ),
                        ),
                        'htmlOptions' => array("class" => "pull-right", "style" => "margin-left:10px;"),
                    ));
                    ?>

                </legend>
            </fieldset>
            <?php
            $this->widget('bootstrap.widgets.TbListView', array(
                'dataProvider' => $requestDataProvider,
                'itemView' => '../_requestlist',
                'emptyText' => !isset($_GET['unreply']) ? '<div class="alert alert-info">暂无邀请回答的问题</div>' : '<div class="alert alert-info">暂无未回答的问题</div>',
                'template' => '{items}{pager}',
                'id' => 'request-item',
                'htmlOptions' => array('style' => 'padding-top:0px')
            ));
            ?>
        <?php } ?>
    </div>
    <div class="content-right">
        <?php if (!Yii::app()->user->isGuest) { ?>
            <?php
            $this->widget('bootstrap.widgets.TbMenu', array(
                'type' => 'list',
                'htmlOptions' => array('style' => 'padding-left:0;padding-right:0;margin-bottom:10px;', 'class' => 'well'),
                'encodeLabel' => false,
                'items' => array(
                    array('label' => '我的草稿', 'url' => array('index', 'type' => 'article'), 'icon' => 'icon-file-9', 'active' => (isset($_GET['type']) && $_GET['type'] == "article") ? true : false),
                    array('label' => '我关注的话题', 'url' => array('index', 'type' => 'attentiontopic'), 'icon' => 'icon-checkbox-checked', 'active' => (isset($_GET['type']) && $_GET['type'] == "attentiontopic") ? true : false),
                    array('label' => '我常去的话题', 'url' => array('index', 'type' => 'lovetopic'), 'icon' => 'icon-heart-8', 'active' => (isset($_GET['type']) && $_GET['type'] == "lovetopic") ? true : false),
                    array('label' => '邀请我回答的问题', 'url' => array('index', 'type' => 'help'), 'icon' => 'icon-copy-3 ', 'active' => (isset($_GET['type']) && $_GET['type'] == "help") ? true : false),
                    array('label' => '我邀请回答的问题', 'url' => array('index', 'type' => 'forhelp'), 'icon' => 'icon-question', 'active' => (isset($_GET['type']) && $_GET['type'] == "forhelp") ? true : false),
                    '--',
                    array('label' => '所有问题', 'url' => array('allquestion'), 'icon' => 'icon-question', 'active' => (isset($_GET['type']) && $_GET['type'] == "question") ? true : false),
                    array('label' => '话题广场', 'url' => array('alltopic'), 'icon' => 'icon-grid-5', 'active' => (isset($_GET['type']) && $_GET['type'] == "topic") ? true : false),
                )
            ));
            ?>
        <?php } else { ?>
            <div class="alert alert-info"><?php echo Sys::model()->find()->site_desc; ?></div>
            <?php
            $this->widget('bootstrap.widgets.TbMenu', array(
                'type' => 'list',
                'htmlOptions' => array('style' => 'padding-left:0;padding-right:0;margin-bottom:10px;'),
                'encodeLabel' => false,
                'items' => array(
                    '--',
                    array('label' => '所有问题', 'url' => array('allquestion'), 'icon' => 'icon-question', 'active' => (isset($_GET['type']) && $_GET['type'] == "question") ? true : false),
                    array('label' => '话题广场', 'url' => array('alltopic'), 'icon' => 'icon-grid-5', 'active' => (isset($_GET['type']) && $_GET['type'] == "topic") ? true : false),
                )
            ));
            ?>
        <?php } ?>
        <?php foreach (DesktopSetting::getArray(r) as $key => $value) {
            ?>  <div class="panel panel-default">
                <div class="panel-heading">最新<?php echo $value->app_name; ?><a class="pull-right" title="全部<?php echo $value->app_name; ?>" href="<?php echo $value->app_id == "user" ? $this->createUrl("query", array("q" => "", "type" => "user")) : $this->createUrl("all" . $value->app_id); ?>">全部<?php echo $value->app_name; ?></a></div>
                <div class="panel-body">
                    <?php $this->renderPartial('../templates/_' . $value->app_id . 'grid', array('limit' => $value->app_length)); ?>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
<script type="text/javascript" src="/js/jquery.dragsort-0.5.1.js"></script>
<script type="text/javascript" src="/js/overlib.js"></script>
<script>
    $(document).ready(function() {
        $(document).delegate(".topic-list", 'mouseenter', function() {
            $(this).find("[name='joinTopic']").show();
            $(this).find("[name='loveTopic']").show();
        });
        $(document).delegate(".topic-list", 'mouseleave', function() {
            $(this).find("[name='joinTopic']").hide();
            $(this).find("[name='loveTopic']").hide();
        });
        $(document).delegate("#topic-item .topic-item", 'mouseenter', function() {
            $(this).find("[data-action='delete']").show();
            $(this).find("[data-action='up']").show();
        });
        $(document).delegate("#topic-item .topic-item", 'mouseleave', function() {
            $(this).find("[data-action='delete']").hide();
            $(this).find("[data-action='up']").hide();
        });
        $(document).delegate("#topic-item .topic-item [data-action='delete']", 'click', function() {
            var topic_id = $(this).attr("data-topicid");
            var url = "<?php echo $this->createUrl('removetopic'); ?>";	//alert(url);
            $.post(url, {topic_id: topic_id}, function(data) {  //alert(data);
                if (data.message == "ok") {
                    $.fn.yiiListView.update("topic-item");
                    $.fn.yiiListView.update("topic-list");
                    $("#topic-count").text(data.count);
                } else {
                    alert('移除出错');
                }
            }, 'json');

        });
        $(document).delegate("#topic-item .request-item [data-action='up']", 'click', function() {
            var topic_id = $(this).attr("data-topicid");
            var url = "<?php echo $this->createUrl('uptopic'); ?>";	//alert(url);
            $.post(url, {topic_id: topic_id}, function(data) {  //alert(data);
                if (data.message == "ok") {
                    $.fn.yiiListView.update("topic-item");
                } else {
                    alert('置顶出错');
                }
            }, 'json');

        })
        $(document).delegate("#request-item .request-item", 'mouseenter', function() {
            $(this).find("[data-action='delete']").show();
        });
        $(document).delegate("#request-item .request-item", 'mouseleave', function() {
            $(this).find("[data-action='delete']").hide();
        });
        $(document).delegate("#request-item .request-item [data-action='delete']", 'click', function() {
            var self = $(this);
            var count = $("#request-count").text();
            var request_id = self.attr("data-requestid");
            var url = "<?php echo $this->createUrl('removerequest'); ?>";	//alert(url);
            $.post(url, {request_id: request_id}, function(data) {  //alert(data);
                if (data.message == "ok") {
                    $.fn.yiiListView.update("request-item");
                    $("#request-count").text(count - 1);
                } else {
                    alert(self.text() + '出错');
                }
            }, 'json');

        });
        //拖动效果
        //  $("#topic-item").dragsort();
        $(document).delegate("#topic-item", 'mouseenter', function() {
            $(this).dragsort({
                dragSelector: "div",
                dragBetween: true,
                dragEnd: saveOrder,
                placeHolderTemplate: "<div class='placeHolder'><div></div></div>"
            });
        });
//        $("" + getId() + "").dragsort({dragSelector: "div", dragBetween: true, dragEnd: saveOrder, placeHolderTemplate: "<li class='placeHolder'><div></div></li>"});
        //处理移动，获取所有ul的id值，并处理后返回
        function getId() {
            var arr = new Array();
            $("#topic-item .topic-item").each(function(i) {  //遍历所有的ul，获取li				
                arr[i] = $(this).attr('id');
            });
            var arrId = "#" + arr.join(',#');//alert(arrId);
            return arrId;
        }
        function saveOrder() {
            var list1New = getId();//变换顺序后的值a
            var url = "<?php echo $this->createUrl('NewSort'); ?>";	//alert(url);
            $.post(url, {list: list1New}, function(data) {  //alert(data);
                if (parseInt(data) < 0) {
                    //alert('移动出错');
                    return false;
                }
            });
        }
    })
</script>