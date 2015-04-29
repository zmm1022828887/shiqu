<?php if (!empty($successMessage)): ?>
    <div class="alert alert-block alert-success">
        <?php
        for ($i = 0; $i < count($successMessage); $i++) {
            echo $successMessage[$i];
        };
        ?>
        <a class="close" data-dismiss="alert" title="关闭">×</a>
    </div>
<?php endif; ?>
<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'sys-form',
    'type' => 'horizontal',
    'clientOptions' => array(
        'validateOnSubmit' => true,
        'validateOnChange' => false,
    ),
    'enableAjaxValidation' => true,
    'htmlOptions' => array('style' => 'margin-top:30px;'),
        ));
echo $form->hiddenField($model, 'id');
echo $form->textFieldRow($model, 'site_name');
echo $form->textFieldRow($model, 'domain_name');
echo $form->textFieldRow($model, 'mail');
echo $form->textAreaRow($model, 'browser_title');
echo $form->textFieldRow($model, 'copyright');
echo $form->textAreaRow($model, 'site_desc', array('style' => 'height:100px;width:400px;resize:none'));
echo $form->textAreaRow($model, 'status_text', array('style' => 'height:100px;width:400px;resize:none','hint'=>'(多行文字可以实现轮换显示)'));
echo $form->select2Row($model, 'identity', array('asDropDownList' => false, 'class' => 'span10', 'options' => array('tags' => array(), 'tokenSeparators' => array(',', ' '))));
echo $form->select2Row($model, 'profession', array('asDropDownList' => false, 'class' => 'span10', 'options' => array('tags' => array(), 'tokenSeparators' => array(',', ' '))));
echo $form->select2Row($model, 'hobbies', array('asDropDownList' => false, 'class' => 'span10', 'options' => array('tags' => array(), 'tokenSeparators' => array(',', ' '))));
?>
<?php
$this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit', 'label' => '保存', 'type' => 'info', 'htmlOptions' => array('style' => 'margin-left:180px;')));
$this->endWidget();
?>
