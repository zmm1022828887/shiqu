<?php
$comment =  Yii::app()->db->createCommand('select *,count(user_id) as num  from comment group by user_id  order by num desc')->queryAll();
foreach ($comment as $k) {
    $i++;
    if ($i == 6) {
        break;
    }
    ?>
    <ul style="padding-left: 6px;">
        <li style="position: relative;border-bottom:1px dotted #ccc;height: 30px;line-height: 30px;"><b class="T_total" style="margin-right:10px;"><?php echo "第".$i."名";?></b><a  target="_blank" href="<?php echo $this->createUrl('default/userinfo', array('user_id' => $k['user_id'])); ?>" title="<?php echo '查看'.User::getNameById($k['user_id']).'信息'; ?>"><b><?php echo User::getNameById($k['user_id']); ?></b></a><span style="position:absolute;right:0;"><b class="T_total"><?php echo $k['num'];?></b>条评论</span></li>
    </ul>
<?php } ?>