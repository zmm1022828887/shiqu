<?php $this->beginWidget('bootstrap.widgets.TbModal', array('id' => 'questionModal', 'options' => array("backdrop" => 'static'), 'fade' => false)); ?>
<div class="modal-header">
    <a class="close" data-dismiss="modal">×</a>
    <h4 class="modal-title" id="myModalLabel">提问</h4> 
</div>
<div class="modal-body" style="max-height:500px;">
    <?php $this->renderPartial('../_questionform', array("questionModel" =>$questionModel, 'action' => $action)); ?>
</div>
<div class="modal-footer" style="text-align: center;">
    <?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'button',
        'type' => 'info',
        'label' => '保存',
        'htmlOptions' => array('onclick' => 'submitQuestion()'),
    ));
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'button',
        'label' => '关闭',
        'htmlOptions' => array("data-dismiss" => "modal"),
    ));
    ?>
</div>
<?php $this->endWidget(); ?>