<?php
/* @var $this DiaryController */
/* @var $model Diary */
/* @var $form CActiveForm */
?>

<div class="form">

    <?php
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => 'link-form',
        'clientOptions' => array(
            'validateOnSubmit' => false, //true 获取不到ckeditor中的content
            'validateOnChange' => false,
        ),
        'enableAjaxValidation' => true,
        //  'enableClientValidation' => true,
        'type' => 'horizontal',
    ));
    ?>

    <p class="note">带有<span class="required">*</span> 字段为必填项.</p>
    <?php echo $form->errorSummary($model); ?>
    <?php echo $form->textFieldRow($model, 'name'); ?>
    <?php echo $form->textFieldRow($model, 'url'); ?>
    <?php echo $form->textFieldRow($model, 'no'); ?>
    <?php echo $form->textAreaRow($model, 'desc'); ?>
    <?php
    echo $form->toggleButtonRow($model, 'status', array(
        'options' => array(
            'enabledLabel' => '显示',
            'disabledLabel' => '隐藏',
            'enabledStyle' => 'success',
            'disabledStyle' => 'error',
        )
    ));
    ?>
    <div class="form-actions">
        <?php
        $this->widget('bootstrap.widgets.TbButton', array(
            'type' => 'info',
            'label' => $model->isNewRecord ? '新建' : '保存',
            'buttonType' => 'submit',
            'htmlOptions' => array('style' => 'margin-right:10px;'),
        ));
        ?>
    </div>
    <?php $this->endWidget(); ?>
</div><!-- form -->