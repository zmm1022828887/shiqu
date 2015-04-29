<?php if ($type == "new"): ?>
    <?php
    $syscomment = SysComment::model()->findAll("is_show=0", array("order" => "create_time desc"));
    $i = 0;
    if (empty($syscomment)) {
        echo "<div class='alert alert-block alert-info'>暂无最新点评.</div>";
    } else {
        foreach ($syscomment as $name) {
            $i++;
            if ($i == 6) {
                break;
            }
            ?>
            <div class="product-comment" style="padding-left: 6px;">
                <div class="comment-list clearfix">
                    <div class="item clearfix" style="margin-bottom: 6px;position: relative;border-bottom:1px dotted #ccc;"> 
                        <div  style="width:100%;float: left;">      
                            <div class="u-name"> <a  target="_blank" href="<?php echo $this->createUrl('default/userinfo', array('user_id' => $name->user_id)); ?>"><b><?php echo User::getNameById($name->user_id); ?></b></a> 点评 <span style="position:absolute;right:0;"><?php echo Comment::timeintval($name->create_time); ?></span></div>
                            <div class="small-comment ellipsis"><?php echo $name->content; ?></div>
                        </div>  
                    </div>
                </div>
            </div>
        <?php
        }
    }
    ?>
<?php elseif ($type == "hot"): ?>
    <?php
    $idString = SysComment::model()->getShowIdStr();
    if ($idString != "") {
        $comment = Yii::app()->db->createCommand('select *,count(comment_id) as num  from sys_comment_reply where is_show=0 and comment_id in (' . $idString . ') group by comment_id  order by num desc')->queryAll();
    } else {
        $comment = Yii::app()->db->createCommand('select *,count(comment_id) as num  from sys_comment_reply where is_show=0 and comment_id =0 group by comment_id  order by num desc')->queryAll();
    }
    $i = 0;
    $count = array();
    if (empty($comment)) {
        echo "<div class='alert alert-block alert-info'>暂无最热点评.</div>";
    } else {
        foreach ($comment as $name) {
            $i++;
            $model = SysComment::model()->findByPk($name['comment_id']);
            if ($i == 6) {
                break;
            }
            ?>
            <div class="agree-comment" style="padding-left: 6px;">
                <div class="comment-list clearfix">
                    <div class="item clearfix" style="margin-bottom: 6px;position: relative;border-bottom:1px dotted #ccc;"> 
                        <div  style="width:100%;float: left;">      
                            <div class="u-name"> <a  target="_blank" href="<?php echo $this->createUrl('default/userinfo', array('user_id' => $model->user_id)); ?>"><b><?php echo User::getNameById($model->user_id); ?></b></a> 于 <?php echo date("Y-m-d H:i:s", $model->create_time); ?> 点评 <a  href="<?php echo $this->createUrl('default/diary', array('id' => $model->id, 'action' => 'view')); ?>"></a><span style="position:absolute;right:0;"><b class="T_total"><?php echo $name['num']; ?></b>人参与回复</span></div>
                            <div class="small-comment ellipsis"><?php echo $model->content; ?></div>
                        </div>  
                    </div>
                </div>
            </div>
        <?php
        }
    }
    ?>
<?php else: ?>
    <?php
    $comment = Yii::app()->db->createCommand('select *,count(user_id) as num  from sys_comment where is_show = 0 group by user_id  order by num desc')->queryAll();
    if (empty($comment)) {
        echo "<div class='alert alert-block alert-info'>暂无点评排行榜.</div>";
    } else {
        foreach ($comment as $k) {
            $i++;
            if ($i == 6) {
                break;
            }
            ?>
            <ul style="padding-left: 6px;">
                <li style="position: relative;border-bottom:1px dotted #ccc;height: 30px;line-height: 30px;"><b class="T_total" style="margin-right:10px;"><?php echo "第" . $i . "名"; ?></b><a  target="_blank" href="<?php echo $this->createUrl('default/userinfo', array('user_id' => $k['user_id'])); ?>" title="<?php echo '查看' . User::getNameById($k['user_id']) . '信息'; ?>"><b><?php echo User::getNameById($k['user_id']); ?></b></a><span style="position:absolute;right:0;">共<b class="T_total"><?php echo $k['num']; ?></b>条点评</span></li>
            </ul>
        <?php
        }
    }
    ?>
<?php endif; ?>