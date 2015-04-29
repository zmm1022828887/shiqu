<?php $this->beginWidget('bootstrap.widgets.TbModal', array('id' => 'topicModal', 'options' => array("backdrop" => 'static'), 'fade' => false)); ?>
<div class="modal-header">
    <a class="close" data-dismiss="modal">×</a>
    <h4 class="modal-title" id="myModalLabel">创建话题</h4> 
</div>
<div class="modal-body" style="max-height:500px;">
    <?php $this->renderPartial('../_topicform', array("topicModel" =>$topicModel, 'action' => $action)); ?>
</div>
<div class="modal-footer" style="text-align: center;">
    <?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'button',
        'type' => 'info',
        'label' => '保存',
        'htmlOptions' => array('onclick' => 'submitTopic()'),
    ));
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'button',
        'label' => '关闭',
        'htmlOptions' => array("data-dismiss" => "modal"),
    ));
    ?>
</div>
<?php $this->endWidget(); ?>