<style>
    #search-topic .row{
        margin-top:5px;
    }
    #search-topic  span.required{display: none;}
    #search-topic .span6{width: 400px;}
    #search-topic .form-horizontal .control-label{
        width: 80px;
    }
    #search-topic  .form-horizontal .controls {
        margin-left: 80px;
    }    
</style>
<div id="search-topic" class="search-form" style="display: none;">
    <?php
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'action' => Yii::app()->createUrl($this->route),
        'method' => 'get',
        'type' => 'horizontal',
    ));
    $model = new Topic();
    ?>
    <div class="search_form" align="center">

        <div class="row">
            <div class="span6">
                <?php
                echo $form->dateRangeRow($model, 'create_time', array('prepend' => '<i class="icon-calendar"></i>',
                    'options' => array('format' => 'yyyy/MM/dd', 'callback' => 'js:function(start, end){console.log(start.toString("MMMM d, yyyy") + " - " + end.toString("MMMM d, yyyy"));}'),
                ));
                ?></div>
            <div class="span6">
                <?php echo $form->textFieldRow($model, 'name'); ?></div>
        </div>
    </div>

    <div class="row buttons"  style="clear:both" align="center">
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit', 'type' => 'primary', 'label' => $model->isNewRecord = '查询')); ?>
    </div>
    <?php $this->endWidget(); ?>
</div>
<?php
if ($this->getAction()->getId() != "group")
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'button',
        'label' => '高级搜索',
        'icon' => 'icon-search-2',
        'htmlOptions' => array("id" => "search", "style" => "margin-left:5px;")
    ));
?>
<?php
$criteria = new CDbCriteria;
if ($this->getAction()->getId()=="personal") {
    $criteria->addCondition("create_user = :create_user");
    $criteria->params[':create_user'] = Yii::app()->user->id;
}
$model = $_GET["Topic"];
if ($model['create_time']) {
    $time_arr = explode("-", $model['create_time']);
    $start_time = strtotime(trim($time_arr[0]) . " 00:00:00");
    $end_time = strtotime(trim($time_arr[1]) . " 23:59:59");
    $criteria->addCondition("create_time >= :start_time and create_time <= :end_time");
    $criteria->params[':start_time'] = $start_time;
    $criteria->params[':end_time'] = $end_time;
}
if ($model['title']) {
    $criteria->addSearchCondition('title', trim($model['title']));
}
if (isset($_GET["id"]))
    $criteria->addCondition("group_id = " . $_GET["id"]);
if (isset($_GET["user_id"]))
    $criteria->addCondition("create_user = " . $_GET["user_id"]);
$dataProvider = new CActiveDataProvider('Topic', array(
    'criteria' => $criteria,
        ));
$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'topic-grid',
    'type' => 'striped',
    'template' => '{items}{pager}',
    'rowHtmlOptionsExpression' => 'array("topic-id"=>$data->id)',
    'dataProvider' => $dataProvider,
    'columns' => array(
        array(
            'header' => '话题',
            'name' => 'name',
            'value' => 'CHtml::link($data->name, array("/default/topic","id"=>$data->id),array("title"=>$data->name,"style" => "display:inline-block;width: 300px","class" => "ellipsis"))',
            'type' => 'raw',
            'headerHtmlOptions' => array('style' => 'width: 200px'),
        ),
        //  (($this->getAction()->getId() == "admin") && ((Yii::app()->user->name == "admin" ) || (Yii::app()->user->id == Group::model()->findByPk($_GET["id"])->create_user))) ? array('header' => '状态', 'name' => 'publish', 'value' => '$data->publish=="0"?"显示":"不显示"', 'headerHtmlOptions' => array('style' => 'width: 40px;'),) : array('name' => 'id', 'headerHtmlOptions' => array('style' => 'display:none;width:0 !important'), 'htmlOptions' => array('style' => 'display:none')),
        (!Yii::app()->user->isGuest && ((($this->getAction()->getId() == "admin") && (Yii::app()->user->name == "admin" )) || (($this->getAction()->getId() == "personal")) || (Yii::app()->user->id == Group::model()->findByPk($_GET["id"])->create_user))) ? array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'header' => '操作',
            'template' => '{viewtopic}{updatetopic}{deletetopic}',
            'headerHtmlOptions' => array('style' => 'width:60px;text-align:center;'),
            'buttons' => array(
                'viewtopic' => array(
                    'label' => '',
                    //    'visible' => '($data->publish=="0") ? true : false',
                    'url' => 'Yii::app()->controller->createUrl("topic",array("id"=> $data->id))',
                    'options' => array(
                        'style' => 'margin-right:5px;',
                        'class' => 'icon-eye-2',
                        'title' => '查看话题',
                    ),
                ),
                'updatetopic' => array(
                    'label' => '',
                    'url' => '',
                    'options' => array(
                        'style' => 'margin-right:5px;cursor:pointer',
                        'class' => 'icon-pencil',
                        'title' => '修改话题',
                        'data-op' => 'updateTopic',
                    ),
                ),
                'deletetopic' => array(
                    'label' => '',
                    'visible' => '((Yii::app()->user->name == "admin") || ($data->create_user== Yii::app()->user->id)) ? true : false',
                    'url' => 'Yii::app()->controller->createUrl("deletetopic")',
                    'options' => array(
                        'style' => 'margin-right:5px;',
                        'class' => 'icon-remove-2',
                        'title' => '删除话题',
                    ),
                    'click' => "function(){
                    var hrefUrl = $(this).attr('href');
                    var id = $(this).parents('tr').attr('topic-id');
                     var title = '删除话题的同时，此话题下的评论和回复会全部删除，确定要删除吗？';
    
                if(window.confirm(title)){
                $.ajax({  
                    url:hrefUrl,
                    data: {'id':id},
                    type:'GET',  
                    success: function(data){
                      $.fn.yiiGridView.update('topic-grid');
                    }
                });
            }else{
                return false;
            }
            return false;
           
            }",
                ),
            ),
                ) : array('name' => 'id', 'headerHtmlOptions' => array('style' => 'display:none;width:0 !important'), 'htmlOptions' => array('style' => 'display:none')),
    ))
);
?>
<script>
    $(document).ready(function() {
        $('#search').live("click", function() {
            if ($('.search-form').is(':visible') == false) {
                $('.search-form').show(500);
            } else {
                $('.search-form').hide(500);
            }
        });
        $('.search-form form').submit(function() {
            $.fn.yiiGridView.update('topic-grid', {
                data: $(this).serialize()
            });
            return false;
        });
    });
</script>