<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$criteria = new CDbCriteria;
$criteria->order = "create_time";
$criteria->limit = $limit;
$models = Topic::model()->findAll($criteria);
if (empty($models)) {
    echo "暂无话题";
} else {
    foreach ($models as $key => $value) {
        ?> 
        <a class="topic-label" data-id="<?php echo $value->id; ?>" href="javascript:;" title="<?php echo $value->name; ?>"><span class="label"><?php echo $value->name; ?></span></a>
        <?php
    }
}
?>