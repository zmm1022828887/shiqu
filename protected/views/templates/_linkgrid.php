<?php Yii::app()->clientScript->registerScript("", "$('[rel=popover]').popover();", CClientScript::POS_READY) ?>
<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$criteria = new CDbCriteria;
$criteria->order = "no";
$criteria->addCondition("status=1");
$criteria->limit = $limit;
$models = Link::model()->findAll($criteria);
if (empty($models)) {
    echo "暂无话题";
} else {
    foreach ($models as $key => $value) {
        ?> 
        <?php
        echo CHtml::Link('<span class="label">'.$value->name.'</span>', $value->url, array(
            'rel' => 'popover',
            'data-placement' => 'top',
            'data-trigger' => 'hover',
            'data-title' => $value->name,
            'data-content' => $value->desc,
            'target'=>'_blank',
        ))
        ?>
        <?php
    }
}
?>