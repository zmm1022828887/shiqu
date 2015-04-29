<?php
if ($type != "hot") {
    $this->widget('bootstrap.widgets.TbGroupGridView', array(
        'id' => $type.'-question-grid',
        'dataProvider' => Question::model()->search($type),
        'extraRowColumns' => array('create_time'),
        'template' => '{items}{pager}',
        'ajaxUpdate' => true,
        'emptyText'=>'<div class="alert alert-info">暂无问题</div>',
        'hideHeader' => true,
        'extraRowExpression' => 'User::dateToText($data->update_time,"long")',
        'columns' => array(
            array(
                'name' => 'create_time',
                'value' => 'User::dateToText($data->update_time,"short")',
                'headerHtmlOptions' => array('style' => 'display:none;'),
                'htmlOptions' => array('style' => 'display:none;'),
            ),
            array(
                'name' => 'title',
                'value' => array($this, 'renderDiv'),
                'type' => 'raw',
                'headerHtmlOptions' => array('style' => 'width: 100%'),
                'htmlOptions' => array('class' => 'ellipsis list-product'),
            ),
        ),
    ));
} else {
    $this->widget('bootstrap.widgets.TbGroupGridView', array(
        'id' => 'question-grid',
        'dataProvider' => Vote::model()->search(),
        'template' => '{items}{pager}',
         'emptyText'=>'<div class="alert alert-info">暂无问题</div>',
        'ajaxUpdate' => true,
        'hideHeader' => true,
        'columns' => array(
            array(
                'name' => 'title',
                'value' => array($this, 'renderVoteDiv'),
                'type' => 'raw',
                'headerHtmlOptions' => array('style' => 'width: 100%'),
                'htmlOptions' => array('class' => 'ellipsis list-product'),
            ),
        ),
    ));
}
?>