<style>
    #search-reply .row{
        margin-top:5px;
    }
    #search-reply  span.required{display: none;}
    #search-reply .span6{width: 400px;}
    #search-reply .form-horizontal .control-label{
        width: 80px;
    }
    #search-reply  .form-horizontal .controls {
        margin-left: 80px;
    }    
</style>
<div id="search-reply" class="search-form" style="display: none;">
    <div class="search_form" align="center">
        <?php
        $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
            'action' => Yii::app()->createUrl($this->route),
            'method' => 'get',
            'type' => 'horizontal',
                ));
        $model = new SysCommentReply();
        ?>
        <div class="row">
            <div class="span6">
                <?php
                echo $form->dateRangeRow($model, 'create_time', array('prepend' => '<i class="icon-calendar"></i>',
                    'options' => array('format' => 'yyyy/MM/dd', 'callback' => 'js:function(start, end){console.log(start.toString("MMMM d, yyyy") + " - " + end.toString("MMMM d, yyyy"));}'),
                ));
                ?></div>
            <div class="span6">
                <?php echo $form->textFieldRow($model, 'user_id', array('maxlength' => 100)); ?>	
            </div>
        </div>
        <div class="row">
            <div class="span6">
                <?php echo $form->textFieldRow($model, 'reply_user_id', array('maxlength' => 100)); ?>
            </div>
            <div class="span6">
                <?php echo $form->textFieldRow($model, 'content', array('maxlength' => 100)); ?>	
            </div>
        </div>
        <div class="row buttons"  style="clear:both" align="center">
            <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit', 'type' => 'primary', 'label' => $model->isNewRecord = '查询')); ?>


        </div>


        <?php $this->endWidget(); ?>
    </div><!-- search-form -->
</div><!-- search-form -->
<?php
$this->widget('bootstrap.widgets.TbButton', array(
    'buttonType' => 'button',
    'label' => '高级搜索',
    'icon' => 'icon-search-2',
    'htmlOptions' => array("id" => "search", "style" => "margin-left:5px;")
));
?>
<?php
$columns = array(
    array('name' => 'reply_user_id','type' => 'raw', 'value' => 'CHtml::openTag("img", array("src" => Yii::app()->controller->createUrl("getimage",array("id"=>$data->user_id,"type"=>"avatar")),"style"=>"margin-right:4px;height:30px;width:30px;")).CHtml::link(User::getNameById($data->reply_user_id),array("userinfo","user_id"=>$data->reply_user_id),array("target"=>"_blank","data-id"=>$data->reply_user_id,"class"=>"user-label"))',),
    array('name' => 'content', 'value' => '$data->content'),
    array('name' => 'create_time', 'value' => 'date("Y-m-d H:i:s",$data->create_time)'),
    array('header' => '状态', 'name' => 'is_show', 'value' => '$data->is_show=="0"?"显示":"不显示"'),
    array(
        'class' => 'bootstrap.widgets.TbButtonColumn',
        'header' => '操作',
        'template' => '{showreply}{noshow}{deletereply}',
        'headerHtmlOptions' => array('style' => 'width:100px;text-align:center;'),
        'buttons' => array(
            'showreply' => array(
                'icon' => 'icon-checkmark-2',
                'label' => '显示',
                'visible' => '$data->is_show=="1" ? true: false',
                'url' => 'Yii::app()->controller->createUrl("changesysreplyshow",array("id"=>$data->primaryKey))',
                'options' => array(
                    'style' => 'margin-left:5px;'
                ),
                'click' => "function(){
                                    $.fn.yiiGridView.update('sys-reply-grid', {
                                        type:'GET',
                                        url:$(this).attr('href'),
                                        success:function(data) {
                                              $.fn.yiiGridView.update('sys-reply-grid');
                                        }
                                    })
                                    return false;
                              }
                     ",
            ),
            'noshow' => array(
                'icon' => 'icon-close-2',
                'label' => '不显示',
                'visible' => '$data->is_show=="0" ? true: false',
                'url' => 'Yii::app()->controller->createUrl("changesysreplyshow",array("id"=>$data->primaryKey))',
                'options' => array(
                    'style' => 'margin-left:5px;'
                ),
                'click' => "function(){
                                    $.fn.yiiGridView.update('sys-reply-grid', {
                                        type:'GET',
                                        url:$(this).attr('href'),
                                        success:function(data) {
                                          if(data=='ok'){
                                              $.fn.yiiGridView.update('sys-reply-grid');
                                            }else{
                                              alert('设置失败');
                                             }
                                        }
                                    })
                                    return false;
                              }
                     ",
            ),
            'deletereply' => array(
                'icon' => 'icon-remove',
                'label' => '删除',
                'url' => 'Yii::app()->controller->createUrl("deletesysreply")',
                'options' => array(
                    'style' => 'margin-left:5px;'
                ),
                'click' => "function(){
                        var hrefUrl =$(this).attr('href');
                        var id = $(this).parents('tr').attr('reply-id');
                if(window.confirm('确定要删除所选的回复吗?')){
                $.ajax({  
                    url:hrefUrl,
                    type:'POST',
                    data: {'id':id},
                    dataType:'html',     
                    success: function(data){
                        if(data=='ok'){
                            alert('删除成功');
                              $.fn.yiiGridView.update('sys-reply-grid');
                        }else{
                            alert('删除失败');
                        }
                    }
                });
                return false;  
            }
               return false;
            }",
            ),
        ),
    ),
);
$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'sys-reply-grid',
    'type' => 'striped',
    'rowHtmlOptionsExpression' => 'array("reply-id"=>$data->id)',
    'dataProvider' => SysCommentReply::model()->search(),
    'columns' => $columns
));
?>
<script>
    $(document).ready(function(){
        $('#search').live("click",function (){
            if($('.search-form').is(':visible')==false){
                $('.search-form').show(500);
            }else{
                $('.search-form').hide(500);
            }
        });
        $('.search-form form').submit(function(){
            $.fn.yiiGridView.update('sys-reply-grid', {
                data: $(this).serialize()
            });
            return false;
        });
    });
</script>