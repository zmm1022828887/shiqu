<style>
    #toModal .control-label{width: 70px;}
    #groupModal .controls{margin-left: 80px;}
</style>
<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'topic-form',
    'type' => 'horizontal',
    'action' => $action,
    'htmlOptions' => array('enctype' => 'multipart/form-data'),
        ));
?>
<?php
$score = Sys::model()->getvaluesByType("topic_score");
if (Sys::model()->getvaluesByType("topic_type") == "0") {
    $hint = "新建话题奖励你" . $score . "个财富值";
} else {
    $hint = "新建话题会花掉你" . $score . "个财富值";
}
echo $form->textFieldRow($topicModel, 'name', array("hint" => $hint));
echo $form->textAreaRow($topicModel, 'desc');
echo $form->dropDownListRow($topicModel, 'parent_id', Topic::model()->listTopics(0, '', '', '默认话题'), array('encode' => false));
echo $form->fileFieldRow($topicModel, 'logo', array('hint' => '上传图片格式为：gif、jpg、jpeg、png'));
?>
<?php $this->endwidget(); ?>