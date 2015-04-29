<?php

$criteria = new CDbCriteria;
$criteria->limit=18;
if ($this->getAction()->getId() == "userinfo") {
    $criteria->addCondition("to_user=" . $user_id);
} else {
    if (($type == "visited") || ($type == "visit")) {
        $criteria->addCondition("is_visit=1");
        if ($type == "visited")
            $criteria->addCondition("to_user=" . $user_id);
        else
            $criteria->addCondition("from_user=" . $user_id);
    }else {
        $criteria->addCondition("is_visit=0");
        $criteria->addCondition("to_user=" . $user_id);
    }
}
$dataProvider = new CActiveDataProvider('Visit', array(
    'criteria' => $criteria,
    'sort' => array(
        'defaultOrder' => 'create_time desc'
    ),
    'pagination' => array(
        'pageVar' => 'page',
        'pageSize' => 9)
        )
);
$this->widget('bootstrap.widgets.TbListView', array(
    'dataProvider' => $dataProvider,
    'itemView' => ($type == "visit") ? '../_touserlist' : '../_userlist',
    'id' => $type . 'History',
    'emptyText' => '<div class="alert alert-info">没有找到相应的访问记录</div>',
    'template' => '{items}{pager}',
    'htmlOptions' => array('style' => 'padding-top:0px')
));
?>