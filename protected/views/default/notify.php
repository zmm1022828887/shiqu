<style>
    .content{margin: 0 auto; width: 1030px; position: relative;padding-top: 30px;}
    .content legend{border-bottom:none;}
    .content .control-label{text-align: left;}
</style>  
<?php $this->pageTitle = "设置 - " . Yii::app()->name; ?>
<div class="content">
    <div class="tabbable">
        <ul class="nav nav-tabs">
            <li class="<?php echo ($_GET['type'] == 'message' || $_GET['type'] == 'dialogue'  ||  !isset($_GET['type'])) ? "active" : '' ?>">
                <a href="<?php echo $this->createUrl('notify', array('type' => 'message')); ?>">消息管理</a>
            </li>
            <li class="<?php echo $_GET['type'] == 'attention' ? "active" : '' ?>">
                <a href="<?php echo $this->createUrl('notify', array('type' => 'attention')); ?>">关注信息管理</a>
            </li>
            <li class="<?php echo $_GET['type'] == 'report' ? "active" : '' ?>">
                <a href="<?php echo $this->createUrl('notify', array('type' => 'report')); ?>">举报信息管理</a>
            </li>
            <li class="<?php echo $_GET['type'] == 'comment' ? "active" : '' ?>">
                <a href="<?php echo $this->createUrl('notify', array('type' => 'comment')); ?>">评论信息管理</a>
            </li>
        </ul>
        <div class="right-tab tab-content">
            <div class="tab-pane active">
                <?php
              if($_GET["type"] != "dialogue"){
                $this->widget('bootstrap.widgets.TbButton', array(
                    'buttonType' => 'button',
                    'label' => '批量删除',
                    'icon' => 'icon-remove',
                    'htmlOptions' => array("id" => "batchDelete", "style" => "margin-right:5px;")
                ));
                $this->widget('bootstrap.widgets.TbButton', array(
                    'buttonType' => 'button',
                    'label' => '全部删除',
                    'icon' => 'icon-remove',
                    'htmlOptions' => array("id" => "allDelete", "style" => "margin-right:5px;")
                ));
                if ((Yii::app()->user->name == "admin") || ((Yii::app()->user->name != "admin") && ($_GET["type"] != "report"))) {
                    if ((!isset($_GET["action"]) || ($_GET["action"] == "inbox")))
                        $this->widget('bootstrap.widgets.TbButton', array(
                            'buttonType' => 'button',
                            'label' => '标记为已读',
                            'icon' => 'icon-mail-2',
                            'htmlOptions' => array("id" => "batchRead", "style" => "margin-right:5px;")
                        ));
                    if($_GET["type"] != "report"){
                    $this->widget('bootstrap.widgets.TbButtonGroup', array(
                        'toggle' => 'radio',
                        'htmlOptions' => array("class" => "pull-right"),
                        'buttons' => array(
                            array(
                                'active' => (!isset($_GET["action"]) || ($_GET["action"] == "inbox")) ? true : false,
                                'label' => '我接收的',
                                'url' => $this->createUrl("notify", array("type" => isset($_GET["type"]) ? $_GET["type"] : "message", "action" => "inbox")),
                                'icon' => 'icon-redo-2',
                                'htmlOptions' => array(
                                    'rel' => 'tooltip',
                                    'data-original-title' => '我接收的',
                                    'data-placement' => 'bottom',
                                )
                            ),
                            array(
                                'active' => (isset($_GET["action"]) && ($_GET["action"] == "send")) ? true : false,
                                'label' => '我发送的',
                                'url' => $this->createUrl("notify", array("type" => isset($_GET["type"]) ? $_GET["type"] : "message", "action" => "send")),
                                'icon' => 'icon-reply',
                                'htmlOptions' => array(
                                    'rel' => 'tooltip',
                                    'data-original-title' => '我发送的',
                                    'data-placement' => 'bottom',
                                )
                            ),
                        ),
                    ));
                    }
                }
              }
                if ($_GET['type'] == 'message' || !isset($_GET['type']))
                    $this->renderPartial('../_message');
                else if ($_GET['type'] == 'dialogue') {
                    $id = intval($_GET['id']);
                    $model = Message::model()->listDialogue($id);
                    //处理原始数据，增加头像与信息发送方判断
                    foreach ($model->getData() as $v) {
                        if ($v['create_user'] == Yii::app()->user->id)
                            $v['is_me_send'] = true;
                        else
                            $v['is_me_send'] = false;
                    }
                    $this->renderPartial('dialogue', array(
                        'data' => $model->getData(),
                        'total' => $model->totalItemCount
                    ));
                }
                else if ($_GET['type'] == "attention")
                    $this->renderPartial('../_notify', array("type" => "attention"));
                else if ($_GET['type'] == "report")
                    $this->renderPartial('../_notify', array("type" => "report"));
                else
                    $this->renderPartial('../_notify', array("type" => "comment"));
                ?>
            </div>

        </div>
    </div>
</div>   
<script>
    var batcheDeleteUrl = "<?php echo Yii::app()->controller->createUrl("batchdeletenotify"); ?>";
    var batcheReadUrl = "<?php echo Yii::app()->controller->createUrl("batchreadnotify"); ?>";
    var notifyType = "<?php echo!isset($_GET["type"]) ? "message" : $_GET["type"]; ?>"
</script>
<script>
    $(document).ready(function() {
        $("#batchDelete").live("click", function() {
            var data = getIds();
            if (data.length > 0) {
                if (window.confirm("确定要删除所选的祝福吗？")) {
                    $.post(batcheDeleteUrl, {'selectdel[]': data,'type': notifyType}, function(data) {
                        if (data == 'ok') {
                            if (notifyType == "message") {
                                $.fn.yiiGridView.update('message-grid');
                            } else
                                $.fn.yiiGridView.update('notify-grid');
                        }
                    });
                }
            } else {
                alert("请选择要删除的信息");
            }
        });
        $("#batchRead").live("click", function() {
            var data = getIds();
            if (data.length > 0) {
                if (window.confirm("确定要标记为已读吗？")) {
                    $.post(batcheReadUrl, {'selectdel[]': data, 'type': notifyType}, function(data) {
                        if (data == 'ok') {
                            if (notifyType == "message") {
                                $.fn.yiiGridView.update('message-grid');
                            } else
                                $.fn.yiiGridView.update('notify-grid');
                        }
                    });
                }
            } else {
                alert("请选择要标记为已读的信息");
            }
        });
        $("#allDelete").live("click", function() {
            var ids = new Array();
            $("input[name='ids[]']").each(function() {
                if ($(this).attr('checked') != 'checked') {
                    $(this).trigger('click');
                }
            })
            $("input[name='ids[]']:checked").each(function() {
                if ($(this).attr('checked'))
                    ids.push($(this).val());
            })
            if (ids.length > 0) {
                if (window.confirm("确定要删除所选的信息吗？")) {
                    $.post(batcheDeleteUrl, {'selectdel[]': ids,'type': notifyType}, function(data) {
                       if (data == 'ok') {
                            if (notifyType == "message") {
                                $.fn.yiiGridView.update('message-grid');
                            } else
                                $.fn.yiiGridView.update('notify-grid');
                        }
                    });
                }
            } else {
                alert("请选择要删除的信息");
            }

        });
        function getIds() {
            var ids = new Array();
            $('input[name="ids[]"]').each(function() {
                if ($(this).attr('checked'))
                    ids.push($(this).val());
            });
            return ids;
        }
    });

</script>