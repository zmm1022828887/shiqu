<style>
    #toModal .control-label{width: 70px;}
    #groupModal .controls{margin-left: 80px;}
</style>
<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'question-form',
//    'type' => 'horizontal',
    'action' => $action,
        ));
?>
<?php
$score = Sys::model()->getvaluesByType("question_score");
if (Sys::model()->getvaluesByType("question_type") == "0") {
    $hint = "新建问题奖励你" . $score . "个财富值";
} else {
    $hint = "新建问题会花掉你" . $score . "个财富值";
}
echo $form->textFieldRow($questionModel, 'title', array("hint" => $hint));
echo $form->textAreaRow($questionModel, 'content');
echo $form->select2Row($questionModel, 'topic_ids', array('asDropDownList' => false, 'style' => 'width:530px;', 'options' => array('tags' => Topic::getTopicArray("name", 20), 'tokenSeparators' => array(',', ' '), 'maximumSelectionSize' => 5)));
?>
<?php $this->endwidget(); ?>