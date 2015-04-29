<?php
$comment = Comment::model()->findAll(array("order" => "create_time desc"));
$i = 0;
foreach ($comment as $name) {
    $i++;
    if ($i == 6) {
        break;
    }
    ?>
    <div class="product-comment" style="padding-left: 6px;">
        <div class="comment-list clearfix">
            <div class="item clearfix" style="margin-bottom: 6px;position: relative;border-bottom:1px dotted #ccc;"> 
                <div  style="width:100%;float: left;">      
                    <div class="small-comment ellipsis"><?php echo $name->content; ?></div>
                </div>  
            </div>
        </div>
    </div>
<?php } ?>