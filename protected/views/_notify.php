<?php
$listDataProvider = new CArrayDataProvider(Notification::getJSONData($type, false, false, true));
$columns = array(
    array(
        'selectableRows' => 2,
        'class' => 'CCheckBoxColumn',
        'header' => '全选',
        'headerHtmlOptions' => array('width' => '10px'),
        'checkBoxHtmlOptions' => array('name' => 'ids[]'),
    ),
    array('header' => $type == "comment" ? ($_GET["action"] == "send" ? "被评论人":"评论人") : ($type == "attention" ? ($_GET["action"] == "send" ? "被关注人":"关注人"): "举报人"), 'type' => 'raw', 'value' => $_GET["action"] == "send"  ? 'CHtml::openTag("img", array("src" => Yii::app()->controller->createUrl("getimage",array("id"=>$data["reportUserId"],"type"=>"avatar")),"style"=>"margin-right:4px;height:30px;width:30px;")) .CHtml::link($data["reportUser"],array("userinfo","user_id"=>$data["reportUserId"]),array("target"=>"_blank","title"=>"查看".$data["reportUser"]."个人信息"))' :'CHtml::openTag("img", array("src" => Yii::app()->controller->createUrl("getimage",array("id"=>$data["createUserId"],"type"=>"avatar")),"style"=>"margin-right:4px;height:30px;width:30px;")) .CHtml::link($data["createUser"],array("userinfo","user_id"=>$data["createUserId"]),array("target"=>"_blank","title"=>"查看".$data["createUser"]."个人信息"))' ),
    $type == "report" ? array('header' => "被举报人", 'type' => 'raw', 'value' => 'CHtml::openTag("img", array("src" =>  Yii::app()->controller->createUrl("getimage",array("id"=>$data["reportUserId"],"type"=>"avatar")),"style"=>"margin-right:4px;height:30px;width:30px;")) .CHtml::link($data["reportUser"],array("userinfo","user_id"=>$data["reportUserId"]),array("target"=>"_blank","title"=>"查看".$data["reportUser"]."个人信息"))') : array("value" => ""),
    array('header' => $type == "comment" ? "简介" : "说明", 'value' => ( $_GET["action"] == "send" ? ($type != "attention"  ? '"你".str_replace("你","TA",$data["content"])' :'"你".$data["content"]."TA"'):( $type != "attention"  ? '"TA".$data["content"]':'"TA".$data["content"]."你"'))),
    $type == "comment" ? array('header' => "内容",'type' => 'raw', 'value' => 'CHtml::link($data["desc"],$data["dataUrl"],array("title"=>$data["desc"]))') : array('value' => ''),
    array('header' => $type == "comment" ? "评论时间" : ($type == "attention" ? "创建时间" : "举报时间"), 'value' => '$data["createTime"]', 'htmlOptions' => array("style" => "width:120px;")),
    array(
        'header' => '状态',
        'type' => 'raw',
        'value' => '($data["remindFlag"] == 0)  ? CHtml::tag("i", array("class" => "icon-mail-4", "style" => "color:#DF9800", "rel" => "tooltip","title" => "未读"),"") : CHtml::tag("i", array("class" => "icon-mail-4", "style" => "color:#9C9D9C", "rel" => "tooltip","title" => "已读"),"")',
        'htmlOptions' => array('style' => 'text-aling:center;'),
        'headerHtmlOptions' => array('style' => 'width: 30px;text-aling:center;'),
    ),
    array(
        'class' => 'bootstrap.widgets.TbButtonColumn',
        'header' => '操作',
        'template' => '{delete}',
        'headerHtmlOptions' => array('style' => 'width:30px;text-align:center;'),
        'buttons' => array(
            'delete' => array(
                'options' => array(
                    'style' => 'margin-left:5px;'
                ),
                'url' => 'Yii::app()->controller->createUrl("deletenotify",array("id"=>$data["id"]))',
            ),
        ),
    ),
);
$this->widget('bootstrap.widgets.TbGridView', array(
    'type' => 'striped',
    'dataProvider' => $listDataProvider,
    'columns' => $columns,
    'id'=>'notify-grid'
));
?>