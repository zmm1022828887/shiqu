<style>
    .star-score{margin: 0;padding: 0;}
.star-score li{ float: left;width:16px;height: 14px; background: url("images/icon-start.png") no-repeat right top;} 
.star-score li:first-child, .star-score li.active{ float: left;width:16px;height: 14px; background: url("images/icon-start.png") no-repeat left top;} 
.star-score li a{display: inline-block;height: 100%;width: 100%;}
.star-score li:hover{cursor: pointer;}
</style>
<div class="form" style="padding-top:10px;">
    <?php
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => 'comment-form',
        'enableAjaxValidation' => false,
        'enableClientValidation' => true,
        'type' => 'horizontal',
        'action' => $this->createUrl('createcomment', array("id" => $_GET["id"])),
            ));
    ?>
    <?php echo $form->hiddenField($model_comment, 'product_id', array("value" =>$model->id)); ?>
    <?php echo $form->hiddenField($model_comment, 'score',array('value'=>1)); ?>
        <div class="control-group ">
        <label class="control-label required">评分<span class="required">*</span></label>
        <div class="controls">
            <ul class="star-score">
            <li class="star"><a title="1分"></a></li>  
            <li class="star"><a title="2分"></a></li>  
            <li class="star"><a title="3分"></a></li> 
            <li class="star"><a title="4分"></a></li>  
            <li class="star"><a title="5分"></a></li>
            </ul>
        </div>  
    </div>
    <?php echo $form->textAreaRow($model_comment, 'content', array("style" => "width:400px;height:100px;")); ?>
    <div class="form-actions">
        <?php
        $this->widget('bootstrap.widgets.TbButton', array(
            'type' => 'primary',
            'label' => '评价',
            'htmlOptions' => array("id"=>'submit'),
        ));
        ?>
    </div>
    <?php $this->endWidget(); ?>
</div><!-- form -->
<script>
$(document).ready(function(){
   $("#submit").live('click',function(){
       if($("#Comment_content").val() == ""){
           alert("请输入相应的评论");
       }else{
           $("#comment-form").submit();
       }
}); 
$(".star-score li").live("click",function(){
        $(this).addClass("active");
         
        $(this).prevAll().addClass("active");
        $(this).nextAll().removeClass("active");
        $("#Comment_score").val($(this).index()+1);
   }); 
});
</script>