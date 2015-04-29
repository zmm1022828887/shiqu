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
    $model = new Question();
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
                <?php echo $form->textFieldRow($model, 'title'); ?></div>
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
$model = $_GET["Question"];
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
if ($this->getAction()->getId() == "personal") {
    $criteria->addCondition("create_user = :create_user");
    $criteria->params[':create_user'] = Yii::app()->user->id;
}
if ($_GET["type"]=="skilltopic") {
   $criteria->addSearchCondition('topic_ids', ','.trim($_GET['id']).",");
}
$criteria->order = "update_time desc";
$dataProvider = new CActiveDataProvider('Question', array(
    'criteria' => $criteria,
        ));
$this->widget('bootstrap.widgets.TbGroupGridView', array(
    'id' => 'question-grid',
    'type' => 'striped',
    'template' => '{items}{pager}',
    'extraRowColumns' => array('update_time'),
    'extraRowExpression' => 'User::dateToText($data->update_time,"long","grid")',
    'dataProvider' => $dataProvider,
    'columns' => array(
        array(
            'class' => 'CCheckBoxColumn',
            'selectableRows' => 2,
            'value' => '$data->id',
            'headerHtmlOptions' => array('width' => '10px'),
            'checkBoxHtmlOptions' => array('name' => 'selectdel[]', 'style' => 'margin-top:-3px;'),
        ),
        array(
            'header' => '问题',
            'name' => 'title',
            'value' => 'CHtml::link($data->title, array("/default/question","id"=>$data->id),array("title"=>$data->title,"style" => "display:inline-block;width: 300px","class" => "ellipsis"))',
            'type' => 'raw',
            'headerHtmlOptions' => array('style' => 'width: 200px'),
        ),
        array(
            'name' => 'update_time',
            'value' => 'User::dateToText($data->update_time,"short")',
            'headerHtmlOptions' => array('style' => 'display:none;'),
            'htmlOptions' => array('style' => 'display:none;'),
        ),
        (($this->getAction()->getId() != "admin")) ? array('header' => '状态', 'name' => 'answer_id', 'value' => '$data->answer_id=="0"?"待解决":"已解决"', 'headerHtmlOptions' => array('style' => 'width: 40px;'),) :
                array(
            'name' => 'create_user',
            'value' => 'CHtml::openTag("img", array("src" => Yii::app()->controller->createUrl("getimage",array("id"=>$data->create_user,"type"=>"avatar")),"style"=>"margin-right:4px;height:30px;width:30px;")).CHtml::link(User::getNameById($data->create_user), array("/default/userinfo","user_id"=>$data->create_user),array("data-id"=>$data->create_user,"style" => "display:inline-block;width: 80px","class" => "ellipsis user-label"))',
            'type' => 'raw',
            'headerHtmlOptions' => array('style' => 'width: 120px'),
            'htmlOptions' => array('style' => 'width: 120px,text-align:right'),
                ),
        array(
            'header' => '回答',
            'name' => 'id',
            'headerHtmlOptions' => array('style' => 'width: 40px'),
            'value' => 'Answer::model()->count("question_id=".$data->id)',
        ),
        array(
            'header' => '最后回答',
            'name' => 'id',
            'value' => '$data->getLastAnswer($data->id)',
            'htmlOptions' => array('style' => 'width: 80px;text-align:right'),
            'headerHtmlOptions' => array('style' => 'width: 80px;text-align:right'),
        ),
        (!Yii::app()->user->isGuest && ((($this->getAction()->getId() == "admin") && (Yii::app()->user->name == "admin" )) || (($this->getAction()->getId() == "personal")) || (Yii::app()->user->id == Group::model()->findByPk($_GET["id"])->create_user))) ? array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'header' => '操作',
            'template' => '{viewquestion}{updatequestion}{deletequestion}',
            'headerHtmlOptions' => array('style' => 'width:60px;text-align:center;'),
            'buttons' => array(
                'viewquestion' => array(
                    'label' => '',
                    'url' => 'Yii::app()->controller->createUrl("question",array("id"=> $data->id))',
                    'options' => array(
                        'style' => 'margin-right:5px;',
                        'class' => 'icon-eye-2',
                        'title' => '查看问题',
                    ),
                ),
                'updatequestion' => array(
                    'label' => '',
                    'url' => '',
                    'visible' => '(((Yii::app()->user->name == "admin") || ($data->create_user== Yii::app()->user->id)) && ($data->answer_id==0)) ? true : false',
                    'options' => array(
                        'style' => 'margin-right:5px;cursor:pointer',
                        'class' => 'icon-pencil',
                        'title' => '修改问题',
                        'data-op' => 'updateQuestion',
                    ),
                ),
                'deletequestion' => array(
                    'label' => '',
                    'visible' => '((Yii::app()->user->name == "admin") || ($data->create_user== Yii::app()->user->id)) ? true : false',
                    'url' => 'Yii::app()->controller->createUrl("deletequestion")',
                    'options' => array(
                        'style' => 'margin-right:5px;',
                        'class' => 'icon-remove-2',
                        'title' => '删除问题',
                    ),
                    'click' => "function(){
                    var hrefUrl = $(this).attr('href');
                    var id = $(this).parents('tr').find('input').val();
                     var title = '删除问题的同时，此问题下的回答、评论和回复会全部删除，确定要删除吗？';
    
                if(window.confirm(title)){
                $.ajax({  
                    url:hrefUrl,
                    data: {'id':id},
                    type:'post',  
                    success: function(data){
                      $.fn.yiiGridView.update('question-grid');
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
    )
));
?>
<script>
    $(document).ready(function() {
        var editQuestionUrl = "<?php echo $this->createUrl("editquestion");?>";
        var updateQuestionUrl = "<?php echo Yii::app()->controller->createUrl("updatequestion", array('id' => 'pk')); ?>";    //查看收件箱邮件的action 
        $('#search').live("click", function() {
            if ($('.search-form').is(':visible') == false) {
                $('.search-form').show(500);
            } else {
                $('.search-form').hide(500);
            }
        });
        $('.search-form form').submit(function() {
            $.fn.yiiGridView.update('question-grid', {
                data: $(this).serialize()
            });
            return false;
        });
        $("[data-op='updateQuestion']").live("click", function() {
            var id = $(this).parents("tr").find("input").val();
            $("#questionModal .modal-header").find("div").hide();
            $("#questionModal").modal({"backdrop": "static", "show": true});
            $.ajax({
                url: editQuestionUrl,
                data: {'id': id},
                type: "POST",
                dataType:'json',
                success: function(data) {
                    $("#questionModal #Question_title").val(data.title);
                    $("#questionModal #Question_content").val(data.content);
                      $("#questionModal .select2-choices li:last-child").prevAll().remove();
                    for(var i=0;i<data.topic_names.length;i++){
                        var li = '<li class="select2-search-choice"><div title="'+data.topic_names[i]+'">'+data.topic_names[i]+'</div><a href="#" onclick="return false;" class="select2-search-choice-close" tabindex="-1"></a></li>';
                        $("#questionModal .select2-choices li:last-child").before(li);
                    }
                    var new_updateUrl = updateQuestionUrl.replace('pk', data.id);
                    $("#questionModal #Question_topic_ids").val(data.topic_ids);
                    $("#questionModal form").attr({"action": new_updateUrl});
                }
            });
            return false;
        });
    });
</script>