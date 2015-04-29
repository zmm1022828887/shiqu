<style>
    #search-comment .row{
        margin-top:5px;
    }
    #search-comment  span.required{display: none;}
    #search-comment .span6{width: 400px;}
    #search-comment .form-horizontal .control-label{
        width: 80px;
    }
    #search-comment  .form-horizontal .controls {
        margin-left: 80px;
    }    
</style>
<div id="search-comment" class="search-form" style="display: none;">
    <div class="search_form" align="center">
        <?php
        $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
            'action' => Yii::app()->createUrl($this->route),
            'method' => 'get',
            'type' => 'horizontal',
                ));
        $model = new SysComment();
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
                <?php echo $form->textFieldRow($model, 'tags', array('maxlength' => 100)); ?>	
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
</div>
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
    array('header' => '用户名','type' => 'raw', 'value' => 'CHtml::openTag("img", array("src" => Yii::app()->controller->createUrl("getimage",array("id"=>$data->user_id,"type"=>"avatar")),"style"=>"margin-right:4px;height:30px;width:30px;")).CHtml::link(User::getNameById($data->user_id),array("userinfo","user_id"=>$data->user_id),array("target"=>"_blank","data-id"=>$data->user_id,"class"=>"user-label"))',),
    array('name' => 'content', 'value' => '$data->content'),
    array('name' => 'tags', 'value' => '$data->tags'),
    array('header' => '点评时间', 'name' => 'create_time', 'value' => 'date("Y-m-d H:i:s",$data->create_time)'),
    array('header' => '状态', 'name' => 'is_show', 'value' => '$data->is_show=="0"?"显示":"不显示"'),
    array(
        'class' => 'bootstrap.widgets.TbButtonColumn',
        'header' => '操作',
        'template' => '{showcomment}{noshow}{deletecomment}',
        'headerHtmlOptions' => array('style' => 'width:60px;text-align:center;'),
        'buttons' => array(
            'showcomment' => array(
                'icon' => 'icon-checkmark-2',
                'label' => '显示',
                'visible' => '$data->is_show=="1" ? true: false',
                'url' => 'Yii::app()->controller->createUrl("changesyscommentshow",array("id"=>$data->primaryKey))',
                'options' => array(
                    'style' => 'margin-left:5px;'
                ),
                'click' => "function(){
                                    $.fn.yiiGridView.update('sys-comment-grid', {
                                        type:'GET',
                                        url:$(this).attr('href'),
                                        success:function(data) {
                                              $.fn.yiiGridView.update('sys-comment-grid');
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
                'url' => 'Yii::app()->controller->createUrl("changesyscommentshow",array("id"=>$data->primaryKey))',
                'options' => array(
                    'style' => 'margin-left:5px;'
                ),
                'click' => "function(){
                                    $.fn.yiiGridView.update('sys-comment-grid', {
                                        type:'GET',
                                        url:$(this).attr('href'),
                                        success:function(data) {
                                          if(data=='ok'){
                                              $.fn.yiiGridView.update('sys-comment-grid');
                                            }else{
                                              alert('设置失败');
                                             }
                                        }
                                    })
                                    return false;
                              }
                     ",
            ),
            'deletecomment' => array(
                'icon' => 'icon-remove',
                'label' => '删除',
                'url' => 'Yii::app()->controller->createUrl("deletesyscomment")',
                'options' => array(
                    'style' => 'margin-left:5px;'
                ),
                'click' => "function(){
                        var hrefUrl =$(this).attr('href');
                        var id = $(this).parents('tr').attr('comment-id');
                if(window.confirm('删除评论时，此评论下的回复也会全部删除，确定要删除所选的评论吗?')){
                $.ajax({  
                    url:hrefUrl,
                    type:'POST',
                    data: {'id':id},
                    dataType:'html',     
                    success: function(data){
                        if(data=='ok'){
                            alert('删除成功');
                              $.fn.yiiGridView.update('sys-comment-grid');
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
    'id' => 'sys-comment-grid',
    'type' => 'striped',
    'rowHtmlOptionsExpression' => 'array("comment-id"=>$data->id)',
    'dataProvider' => SysComment::model()->search(),
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
            $.fn.yiiGridView.update('sys-comment-grid', {
                data: $(this).serialize()
            });
            return false;
        });
    });
</script>