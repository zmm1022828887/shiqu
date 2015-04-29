<?php
/* @var $this DiaryController */
/* @var $model Diary */
/* @var $form CActiveForm */
?>

<div class="form">

    <?php
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => 'article-form',
        'enableAjaxValidation' => true,
        'enableClientValidation' => true,
        
//        'type' => 'horizontal',
    ));
    ?>

    <p class="note">带有<span class="required">*</span> 字段为必填项.</p>
    <?php echo $form->errorSummary($model); ?>
    <?php
    $score = Sys::model()->getvaluesByType("article_score");
    if (Sys::model()->getvaluesByType("article_type") == "0") {
        $hint = "发表一篇文章会奖励" . $score . "个财富值";
    } else {
        $hint = "发表一篇文章会花掉" . $score . "个财富值";
    }
    ?>
    <?php echo $form->textFieldRow($model, 'subject', array('hint' => $hint,'style'=>'width:710px')); ?>
    <?php echo $form->ckeditorRow($model, 'content', array('options' => array('toolbar' => 'Simple'))); ?>
    <?php echo $form->select2Row($model, 'topic_ids', array('asDropDownList' => false, 'style' => 'width:710px;', 'options' => array('tags' => Topic::getTopicArray("name", 20), 'tokenSeparators' => array(',', ' '), 'maximumSelectionSize' => 5))); ?>
    <?php echo $form->hiddenField($model, 'publish'); ?>
    <?php
    echo $form->toggleButtonRow($model, 'anonymity_yn', array(
        'options' => array(
            'enabledLabel' => '是',
            'disabledLabel' => '否',
            'enabledStyle' => 'success',
            'disabledStyle' => 'error',
        )
    ));
    ?>
    <div class="form-actions">
        <?php
        $this->widget('bootstrap.widgets.TbButton', array(
            'type' => 'info',
            'label' => $model->isNewRecord ? '发布' : '保存',
            'buttonType' => 'button',
            'htmlOptions' => array('style' => 'margin-right:10px;', 'onclick' => '$("#Article_publish").val(1);$("#article-form").submit();'),
        ));
        if($model->isNewRecord)
        $this->widget('bootstrap.widgets.TbButton', array(
            'label' => '存草稿',
            'buttonType' => 'button',
            'htmlOptions' => array('onclick' => 'margin-right:10px;', 'onclick' => '$("#Article_publish").val(0);$("#article-form").submit();'),
        ));
        ?>
    </div>
    <?php $this->endWidget(); ?>
</div><!-- form -->