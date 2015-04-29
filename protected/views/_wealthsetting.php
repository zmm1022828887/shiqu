<style>
    #update-wealth .control-label{text-align: right;}
    .settings-section {margin-top: 10px;border-bottom: 1px solid #eee;}
    .settings-section  .settings-section-title{padding: 10px 0 5px;}
    .settings-item+.settings-item {border-top: 1px dotted #f2f2f2;}
    .settings-section  .settings-section-title h2{font-weight: 700;font-size: 14px;outline: 0;margin: 0;}
</style>
<?php if (!empty($successMessage)): ?>
    <div class="alert alert-block alert-success">
        <?php
        for ($i = 0; $i < count($successMessage); $i++) {
            echo  $successMessage[$i];
        };
        ?>
        <a class="close" data-dismiss="alert" title="关闭">×</a>
    </div>
<?php endif; ?>
<?php
$sysModal = Sys::model()->find();
$setting_wealth = unserialize($sysModal->setting_wealth);
$model->register_score = $setting_wealth['register_score'];
$model->login_score = $setting_wealth['login_score'];
$model->topic_score = $setting_wealth['topic_score'];
$model->question_score = $setting_wealth['question_score'];
$model->answer_score = $setting_wealth['answer_score'];
$model->article_score = $setting_wealth['article_score'];
$model->register_type = $setting_wealth['register_type'];
$model->login_type = $setting_wealth['login_type'];
$model->topic_type = $setting_wealth['topic_type'];
$model->question_type = $setting_wealth['question_type'];
$model->answer_type = $setting_wealth['answer_type'];
$model->article_type = $setting_wealth['article_type'];
?>
<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'update-wealth',
    'type' => 'horizontal',
        ));
?>
<div class="settings-section clearfix">
    <div class="settings-section-title" style="width:10%;float: left;padding: 0 !important;">
        <h2>注册</h2>
    </div>
    <div class="settings-item clearfix" style="width:90%;float: left;">
        <div class="settings-item-content">
            <div style="width:50%;float: left;">
            <?php echo $form->textFieldRow($model, 'register_score'); ?>
                </div>
                   <div style="width:50%;float: left;">
            <?php echo $form->radioButtonListInlineRow($model, 'register_type', Sys::model()->getType()); ?>
            </div>
        </div>
    </div>
</div>
<div class="settings-section clearfix">
    <div class="settings-section-title" style="width:10%;float: left;padding: 0 !important;">
        <h2>登录</h2>
    </div>
    <div class="settings-item clearfix" style="width:90%;float: left;">
        <div class="settings-item-content">
            <div style="width:50%;float: left;">
            <?php echo $form->textFieldRow($model, 'login_score'); ?>
                </div>
                   <div style="width:50%;float: left;">
            <?php echo $form->radioButtonListInlineRow($model, 'login_type', Sys::model()->getType()); ?>
            </div>
        </div>
    </div>
</div>
<div class="settings-section clearfix">
    <div class="settings-section-title" style="width:10%;float: left;padding: 0 !important;">
        <h2>新建话题</h2>
    </div>
    <div class="settings-item clearfix" style="width:90%;float: left;">
        <div class="settings-item-content">
            <div style="width:50%;float: left;">
            <?php echo $form->textFieldRow($model, 'topic_score'); ?>
                </div>
                   <div style="width:50%;float: left;">
            <?php echo $form->radioButtonListInlineRow($model, 'topic_type', Sys::model()->getType()); ?>
            </div>
        </div>
    </div>
</div>
<div class="settings-section clearfix">
    <div class="settings-section-title" style="width:10%;float: left;padding: 0 !important;">
        <h2>提问</h2>
    </div>
    <div class="settings-item clearfix" style="width:90%;float: left;">
        <div class="settings-item-content">
            <div style="width:50%;float: left;">
            <?php echo $form->textFieldRow($model, 'question_score'); ?>
                </div>
                   <div style="width:50%;float: left;">
            <?php echo $form->radioButtonListInlineRow($model, 'question_type', Sys::model()->getType()); ?>
            </div>
        </div>
    </div>
</div>
<div class="settings-section clearfix">
    <div class="settings-section-title" style="width:10%;float: left;padding: 0 !important;">
        <h2>回答</h2>
    </div>
    <div class="settings-item clearfix" style="width:90%;float: left;">
        <div class="settings-item-content">
            <div style="width:50%;float: left;">
            <?php echo $form->textFieldRow($model, 'answer_score'); ?>
                </div>
                   <div style="width:50%;float: left;">
            <?php echo $form->radioButtonListInlineRow($model, 'answer_type', Sys::model()->getType()); ?>
            </div>
        </div>
    </div>
</div>
<div class="settings-section clearfix">
    <div class="settings-section-title" style="width:10%;float: left;padding: 0 !important;">
        <h2>新建文章</h2>
    </div>
    <div class="settings-item clearfix" style="width:90%;float: left;">
        <div class="settings-item-content">
            <div style="width:50%;float: left;">
            <?php echo $form->textFieldRow($model, 'article_score'); ?>
                </div>
                   <div style="width:50%;float: left;">
            <?php echo $form->radioButtonListInlineRow($model, 'article_type', Sys::model()->getType()); ?>
            </div>
        </div>
    </div>
</div>
<div clasa="actions" style="text-align: left;padding-top: 10px;">
    <?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'submit',
        'type' => 'info',
        'label' => '保存',
    ));
    ?>
</div>
<?php $this->endWidget(); ?>