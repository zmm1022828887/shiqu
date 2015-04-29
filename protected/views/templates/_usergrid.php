<style>
    .content .content-right .member-list {margin-top: -20px;letter-spacing: -0.31em;word-spacing: -0.43em;font-size: 0;}
    .content .content-right .member-list li {display: inline-block;zoom: 1; width: 75px;margin-top: 20px;text-align: center;font-size: 12px;vertical-align: top;letter-spacing: normal; word-spacing: normal;}
    .content .content-right .member-list .pic {margin-bottom: 5px;}
    .content .content-right .member-list .name {clear: both;padding: 0 4px; word-wrap: break-word; word-break: normal;}
</style>
<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$criteria = new CDbCriteria;
$criteria->order = "register_time";
$criteria->limit = $limit;
$models = User::model()->findAll($criteria);
if (empty($models)) {
    echo "暂无用户";
} else {
    echo "<ul class='member-list'>";
    foreach ($models as $key => $value) {
        ?> 
        <li>
            <div class="pic">
                <a  class="user-label clearfix" href="javascript:;" data-id="<?php echo $value->id; ?>">
                    <img height="50" width="50"  src="<?php echo $this->createUrl("getimage", array("id" => $value->id, "type" => "avatar")); ?>" alt="<?php echo User::getNameById($value->id) . " - " . $this->title; ?>"> 
                </a>
            </div>
            <div class="name">
                <a   class="user-label clearfix" href="javascript:;" data-id="<?php echo $value->id; ?>"><?php echo User::getNameById($value->id); ?></a>
            </div>
        </li>
        <?php
    }
    echo "</ul>";
}
?>