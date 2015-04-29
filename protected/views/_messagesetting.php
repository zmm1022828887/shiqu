<style>
    #update-message .controls{margin-left: 0;}
    .settings-section {margin-top: 10px;border-bottom: 1px solid #eee;}
    .settings-section  .settings-section-title{padding: 10px 0 5px;}
    .settings-item+.settings-item {border-top: 1px dotted #f2f2f2;}
    .settings-section  .settings-section-title h2{font-weight: 700;font-size: 14px;outline: 0;margin: 0;}
</style>
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
                'id' => 'update-message',
                'type' => 'horizontal',
            ));
            ?>
<div class="settings-section">
    <div class="settings-section-title">
        <h2>接收哪些消息</h2>
    </div>
    <div class="settings-item clearfix">
        <div class="settings-item-content">
           <?php echo $form->checkBoxListInlineRow($userModel, 'subscribe_member_follow', array('1' => '有人关注我'),array('labelOptions' => array('label' => false, 'style' => 'width:0px;'))); ?>
           <?php echo $form->radioButtonListInlineRow($userModel, 'subscribe_ask_like', array('1' => '所有人','2'=>'我关注的人'),array('labelOptions' => array('style' => 'width:260px;'))); ?>
           <?php echo $form->radioButtonListInlineRow($userModel, 'subscribe_question_like', array('1' => '所有人','2'=>'我关注的人','3'=>'我不接受此类通知'),array('labelOptions' => array('style' => 'width:260px;'))); ?>
           <?php echo $form->radioButtonListInlineRow($userModel, 'subscribe_article_like', array('1' => '所有人','2'=>'我关注的人','3'=>'我不接受此类通知'),array('labelOptions' => array('style' => 'width:260px;'))); ?>
           <?php echo $form->radioButtonListInlineRow($userModel, 'subscribe_answer_like', array('1' => '所有人','2'=>'我关注的人','3'=>'我不接受此类通知'),array('labelOptions' => array('style' => 'width:260px;'))); ?>
           <?php echo $form->radioButtonListInlineRow($userModel, 'subscribe_comment_like', array('1' => '所有人','2'=>'我关注的人','3'=>'我不接受此类通知'),array('labelOptions' => array('style' => 'width:260px;'))); ?>
        </div>
    </div>
    <div class="settings-section-title">
        <h2>谁可以给我发私信</h2>
    </div>
    <div class="settings-item clearfix">
        <div class="settings-item-content">
            <?php echo $form->radioButtonListInlineRow($userModel, 'subscribe_message_like', Message::model()->getType(), array('labelOptions' => array('label' => false, 'style' => 'width:0px;'))); ?>
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