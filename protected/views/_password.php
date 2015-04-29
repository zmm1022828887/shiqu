<?php if (!empty($errorMessage)): ?>
    <div class="alert alert-block alert-error">
        <?php
        for ($i = 0; $i < count($errorMessage); $i++) {
            echo $errorMessage[$i];
        };
        ?>
        <a class="close" data-dismiss="alert" title="关闭">×</a>
    </div>
<?php endif; ?>
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
    'id' => 'update-password',
    'type' => 'horizontal',
    'enableAjaxValidation' => false,
    'enableClientValidation' => true,
        ));
?>
<?php
if (isset($_POST['User'])) {
    $userModel->old_password = $_POST['User']['old_password'];
    $userModel->new_password = $_POST['User']['new_password'];
    $userModel->retype_password = $_POST['User']['retype_password'];
}
?>
<?php echo $form->passwordFieldRow($userModel, 'old_password', array('hint' => '密码不能为空，且密码字符长度不能小于6位')); ?>
<?php echo $form->passwordFieldRow($userModel, 'new_password'); ?>
    <?php echo $form->passwordFieldRow($userModel, 'retype_password'); ?>
<div clasa="actions" style="text-align: center;">
    <?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'submit',
        'type' => 'info',
        'label' => '保存',
    ));
    echo '&nbsp;';
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'reset',
        'label' => '取消',
        'size' => 'larger',
    ));
    ?>
</div><?php
$this->endWidget();
?>