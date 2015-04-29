<?php
$comment =  Yii::app()->db->createCommand('select *,count(comment_id) as num  from comment_reply group by comment_id  order by num desc')->queryAll();
$i = 0;
$count = array();
foreach ($comment as $name) {
    $i++;
    $model = Comment::model()->findByPk($name['comment_id']);
    if ($i == 6) {
        break;
    }
    ?>
    <div class="product-comment" style="padding-left: 6px;">
        <div class="comment-list clearfix">
            <div class="item clearfix" style="margin-bottom: 6px;position: relative;border-bottom:1px dotted #ccc;"> 
                <div  style="width:100%;float: left;">      
                    <div class="small-comment ellipsis"><?php echo $model->content; ?></div>
                </div>  
            </div>
        </div>
    </div>
<?php }?>