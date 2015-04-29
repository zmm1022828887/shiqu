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
</style>  

<?php $this->pageTitle = "我关注的话题 - " . Yii::app()->name; ?>
<div class="content clearfix">
    <div class="content-left">
        <fieldset>
            <legend>话题动态<span class="pull-right" style="font-size: 14px;"><a href="<?php echo $this->createUrl("personal",array("type"=>"jointopic","user_id"=>Yii::app()->user->id));?>">共关注了<?php echo Topic::model()->getAttendtionCount(Yii::app()->user->id); ?>个话题</a></span></legend>
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
    </div>
    <div class="content-right">
        <div class="alert alert-info">将话题固定在边栏，拖动改变他们的位置，以便快速进入</div>
        <fieldset>
            <legend>常去话题<span class="badge badge-info pull-right" style="font-size: 14px;margin-top: 10px;" id="topic-count"><?php echo $topicDataProvider->itemCount; ?></span></legend>
        </fieldset>
        <?php
        $this->widget('bootstrap.widgets.TbListView', array(
            'dataProvider' => $topicDataProvider,
            'itemView' => '../_lovetopiclist',
            'emptyText' => '<div class="alert alert-info">暂无固定的话题</div>',
            'template' => '{items}{pager}',
            'id' => 'topic-item',
            'htmlOptions' => array('style' => 'padding-top:0px', 'id' => 'topic-item')
        ));
        ?>
        <div class="pull-right zg-gray" style="margin-top:10px;"><a href="<?php echo $this->createUrl("alltopic"); ?>"><i class="icon-grid-5"></i>话题广场</a></div>
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
        $(document).delegate("[data-action='delete']", 'click', function() {
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
            },'json');

        });
       $(document).delegate("[data-action='up']", 'click', function() {
            var topic_id = $(this).attr("data-topicid");
            var url = "<?php echo $this->createUrl('uptopic'); ?>";	//alert(url);
            $.post(url, {topic_id: topic_id}, function(data) {  //alert(data);
                if (data.message == "ok") {
                    $.fn.yiiListView.update("topic-item");
                } else {
                    alert('置顶出错');
                }
            },'json');

        })
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