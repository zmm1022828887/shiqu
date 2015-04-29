
<?php

$criteria = new CDbCriteria;
$userId = Yii::app()->user->id;
$toDelete = Message::$msg_stauts['to']['unread_delete_flag'];
if (isset($_GET["read_flag"])) {
    $criteria->addCondition("to_uid = '" . $userId . "' and remind_flag != 0 and delete_flag!= '" . $toDelete . "'");
} else if ($_GET["action"] == "send") {
    $criteria->addCondition("(create_user ='" . $userId . "' and delete_flag != 2)");
} else {
    $criteria->addCondition("(to_uid = '" . $userId . "' and delete_flag != 1)");
}
$criteria->order = "create_time desc";
$dataProvider = new CActiveDataProvider('Message', array(
    'criteria' => $criteria,
    'pagination' => array(
        'pageVar' => 'page',
        'pageSize' => 10)
        ));
$columns = array(
    array(
        'selectableRows' => 2,
        'class' => 'CCheckBoxColumn',
        'header' => '全选',
        'headerHtmlOptions' => array('width' => '10px'),
        'checkBoxHtmlOptions' => array('name' => 'ids[]'),
    ),
    ($_GET["action"] == "send") ? array('name' => 'to_uid', 'value' => 'User::model()->getNameById($data->to_uid)', 'htmlOptions' => array("style" => "width:80px;")) : array('name' => 'create_user', 'value' => 'User::model()->getNameById($data->create_user)', 'htmlOptions' => array("style" => "width:80px;")),
    array('name' => 'content', 'value' => '$data->content'),
    array('name' => 'create_time', 'value' => 'date("Y-m-d H:i:s",$data->create_time)', 'htmlOptions' => array("style" => "width:120px;")),
    array(
        'header' => '状态',
        'type' => 'raw',
        'name' => 'remind_flag',
        'value' => '($data->delete_flag == 1) ? CHtml::tag("i", array("class" => "icon-user-cancel", "style" => "color:#FF0000", "rel" => "tooltip","title" => "收信人已删除"),"") : (($data->remind_flag==1) ?   CHtml::tag("i", array("class" => "icon-mail-4", "style" => "color:#DF9800", "rel" => "tooltip","title" => "收信人未读"),"") : CHtml::tag("i", array("class" => "icon-mail-4", "style" => "color:#9C9D9C", "rel" => "tooltip","title" => "收信人已读"),""))',
        'htmlOptions' => array('style' => 'text-aling:center;'),
        'headerHtmlOptions' => array('style' => 'width: 30px;text-aling:center;'),
    ),
    array(
        'name' => 'id',
        'header' => '操作',
        'value' => array($this, 'renderButtons'),
        'sortable'=>false,
        'htmlOptions' => array('style' => 'text-aling:center;'),
        'headerHtmlOptions' => array('style' => 'width:80px;text-align:center;'),
    )
);
$this->widget('bootstrap.widgets.TbGridView', array(
    'type' => 'striped',
    'id' => 'message-grid',
    'dataProvider' => $dataProvider,
    'columns' => $columns
));
?>
<script>
$(document).ready(function(){
    $("#message-grid").find("a[action-type='delMessage']").live ("click", function(e){
        var id = $(this).attr("action-data-id");
        var url = $(this).attr("href");
          if(window.confirm('确定要删除此对话？')){
         $.post(url, {id: id}, function(){  
           $.fn.yiiGridView.update("message-grid");
        });
    }
        return false;
    });
});
</script>