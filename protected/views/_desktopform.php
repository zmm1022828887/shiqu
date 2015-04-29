<?php
/* @var $this DiaryController */
/* @var $model Diary */
/* @var $form CActiveForm */
?>

<div class="form">

    <?php
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => 'desktop-form',
         'clientOptions' => array(
            'validateOnSubmit' => false,//true 获取不到ckeditor中的content
            'validateOnChange' => false,
        ),
        'enableAjaxValidation' => true,
      //  'enableClientValidation' => true,
        'type' => 'horizontal',
    ));
    ?>

    <p class="note">带有<span class="required">*</span> 字段为必填项.</p>
    <?php echo $form->errorSummary($model); ?>
    <?php echo $form->textFieldRow($model, 'app_name'); ?>
     <?php echo $form->textFieldRow($model, 'app_id'); ?>
     <?php echo $form->textFieldRow($model, 'app_no'); ?>
    <?php
    echo $form->toggleButtonRow($model, 'app_status', array(
        'options' => array(
            'enabledLabel' => '开启',
            'disabledLabel' => '关闭',
            'enabledStyle' => 'success',
            'disabledStyle' => 'error',
        )
    ));
    ?>
    <?php echo $form->radioButtonListInlineRow($model,'app_direction',array('l'=>'居左','r'=>'居右'));?>
         <?php echo $form->textFieldRow($model, 'app_length'); ?>
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