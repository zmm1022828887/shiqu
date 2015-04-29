<?php
$criteria = new CDbCriteria;
$criteria->order = "log_time asc";
$dataProvider = new CActiveDataProvider('SysLog', array(
    'criteria' => $criteria,
        ));
$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'sys-log-grid',
    'type' => 'striped',
    'template' => '{items}{pager}',
    'dataProvider' => $dataProvider,
    'columns' => array(
        array(
            'name' => 'user_name',
            'headerHtmlOptions' => array('style' => 'width: 100px'),
        ),
        array(
            'name' => 'log_time',
            'value'=>'date("Y-m-d H:i:s",$data->log_time)',
            'headerHtmlOptions' => array('style' => 'width: 200px'),
        ),
        array(
            'name' => 'log_ip',
            'headerHtmlOptions' => array('style' => 'width: 200px'),
        ),
        array(
            'name' => 'message',
            'headerHtmlOptions' => array('style' => 'width: 200px'),
        )
    )
));
?>