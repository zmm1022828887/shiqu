<style>
    #search-topic .row{
        margin-top:5px;
    }
    #search-topic  span.required{display: none;}
    #search-topic .span6{width: 50%;width: auto;}
    #search-topic .form-horizontal .control-label{
        width: 80px;
    }
    #search-topic  .form-horizontal .controls {
        margin-left: 80px;
    }    
</style>
<?php if($this->getAction()->getId() != "index"){?> 
<div id="search-topic" class="search-form" style="display: none;">
    <?php
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'action' => Yii::app()->createUrl($this->route),
        'method' => 'get',
        'type' => 'horizontal',
            ));
    $model = new Article();
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
                <?php echo $form->textFieldRow($model, 'subject'); ?></div>
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
<?php }?>
<?php
$criteria = new CDbCriteria;
if ($this->getAction()->getId() == "admin") {
    $criteria->addCondition("publish = 1");
}
if ($this->getAction()->getId() == "index") {
    $criteria->addCondition("publish = 0");
}
if (($this->getAction()->getId()=="personal") || ($this->getAction()->getId()=="index")) {
    $criteria->addCondition("create_user = :create_user");
    $criteria->params[':create_user'] = Yii::app()->user->id;
}
$model = $_GET["Article"];
if ($model['create_time']) {
    $time_arr = explode("-", $model['create_time']);
    $start_time = strtotime(trim($time_arr[0]) . " 00:00:00");
    $end_time = strtotime(trim($time_arr[1]) . " 23:59:59");
    $criteria->addCondition("create_time >= :start_time and create_time <= :end_time");
    $criteria->params[':start_time'] = $start_time;
    $criteria->params[':end_time'] = $end_time;
}
if ($model['subject']) {
    $criteria->addSearchCondition('subject', trim($model['subject']));
}
if ($_GET["type"]=="skilltopic") {
   $criteria->addSearchCondition('topic_ids', '%,'.trim($_GET['id']).",%");
}
$dataProvider = new CActiveDataProvider('Article', array(
            'criteria' => $criteria,
        ));
$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'article-grid',
    'type' => 'striped',
    'template' => '{items}{pager}',
    'rowHtmlOptionsExpression' => 'array("article-id"=>$data->id)',
    'dataProvider' => $dataProvider,
    'columns' => array(
        array(
            'header' => '标题',
            'name' => 'title',
            'value' => '$data->publish=="1" ? CHtml::link($data->subject, array("/default/article","id"=>$data->id),array("title"=>$data->subject,"style" => "display:inline-block;width: 300px","class" => "ellipsis")): CHtml::link($data->subject, "javascript:;",array("title"=>$data->subject,"style" => "display:inline-block;width: 300px","class" => "ellipsis"))',
            'type' => 'raw',
            'headerHtmlOptions' => array('style' => 'width: 200px'),
        ),
        (($this->getAction()->getId() != "admin")) ? array('header' => '状态', 'name' => 'publish', 'value' => '$data->publish=="1"?"已发布":"草稿"', 'headerHtmlOptions' => array('style' =>  Yii::app()->controller->getAction()->getId() == "index"  ? 'display:none;':'width: 40px;'), 'htmlOptions' => array('style' => Yii::app()->controller->getAction()->getId() == "index"  ? 'display:none;':''),) :
                array(
            'header' => '作者',
            'name' => 'create_user',
            'value' => 'CHtml::openTag("img", array("src" => Yii::app()->controller->createUrl("getimage",array("id"=>$data->create_user,"type"=>"avatar")),"style"=>"margin-right:4px;height:30px;width:30px;")).CHtml::link(User::getNameById($data->create_user), array("/default/userinfo","user_id"=>$data->create_user),array("data-id"=>$data->create_user,"style" => "display:inline-block;width: 80px","class" => "ellipsis user-label"))',
            'type' => 'raw',
            'headerHtmlOptions' => array('style' => 'width: 120px'),
            'htmlOptions' => array('style' => 'width: 120px,text-align:right'),
                ),
        array(
            'header' => '回应',
            'name' => 'id',
            'headerHtmlOptions' => array('style' => Yii::app()->controller->getAction()->getId() == "index"  ? 'display:none;':'width: 40px'),
            'htmlOptions' => array('style' => Yii::app()->controller->getAction()->getId() == "index"  ? 'display:none;':''),
            'value' => 'Comment::model()->getCount($data->id,"article")',
        ),
        array(
            'header' => '最后回应',
            'name' => 'id',
            'value' => 'Comment::getLastCommentTime($data->id,"article")',
            'htmlOptions' => array('style' =>  Yii::app()->controller->getAction()->getId() == "index"  ? 'display:none;': 'width: 80px;text-align:right'),
            'headerHtmlOptions' => array('style' => Yii::app()->controller->getAction()->getId() == "index"  ? 'display:none;':  'width: 80px;text-align:right'),
        ),
        (!Yii::app()->user->isGuest && ((($this->getAction()->getId() == "admin") && (Yii::app()->user->name == "admin" )) || (($this->getAction()->getId() == "personal")) || (($this->getAction()->getId() == "index")) || (Yii::app()->user->id == Article::model()->findByPk($_GET["id"])->create_user))) ? array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'header' => '操作',
            'template' => '{showarticle}{viewarticle}{updatearticle}{deletearticle}',
            'headerHtmlOptions' => array('style' => 'width:60px;text-align:center;'),
            'buttons' => array(
                'showarticle' => array(
                    'icon' => 'icon-checkmark-2',
                    'label' => '发表',
                    'visible' => '((Yii::app()->user->name == "admin" || $data->create_user== Yii::app()->user->id) && ($data->publish=="1")) ? false: true',
                    'url' => 'Yii::app()->controller->createUrl("publisharticle",array("id"=>$data->primaryKey))',
                    'options' => array(
                        'style' => 'margin-right:5px;'
                    ),
                    'click' => "function(){
                                    $.fn.yiiGridView.update('article-grid', {
                                        type:'GET',
                                        url:$(this).attr('href'),
                                        success:function(data) {
                                             $.fn.yiiGridView.update('article-grid');
                                        }
                                    })
                                    return false;
                              }
                     ",
                ),
                'viewarticle' => array(
                    'label' => '',
                    'visible' => '($data->publish=="1") ? true : false',
                    'url' => 'Yii::app()->controller->createUrl("article",array("id"=> $data->id))',
                    'options' => array(
                        'style' => 'margin-right:5px;',
                        'class' => 'icon-eye-2',
                        'title' => '查看文章',
                    ),
                ),
                'updatearticle' => array(
                    'label' => '',
                     'url' => 'Yii::app()->controller->createUrl("updatearticle",array("id"=> $data->id))',
                    'options' => array(
                        'style' => 'margin-right:5px;cursor:pointer',
                        'class' => 'icon-pencil',
                        'title' => '修改文章',
                    ),
                ),
                'deletearticle' => array(
                    'label' => '',
                    'visible' => '((Yii::app()->user->name == "admin") || ($data->create_user== Yii::app()->user->id)) ? true : false',
                    'url' => 'Yii::app()->controller->createUrl("deletearticle")',
                    'options' => array(
                        'style' => 'margin-right:5px;',
                        'class' => 'icon-remove-2',
                        'title' => '删除文章',
                    ),
                    'click' => "function(){
                    var hrefUrl = $(this).attr('href');
                    var id = $(this).parents('tr').attr('article-id');
                     var title = '删除文章的同时，此文章下的评论和回复会全部删除，确定要删除吗？';
    
                if(window.confirm(title)){
                $.ajax({  
                    url:hrefUrl,
                    data: {'id':id},
                    type:'post',  
                    success: function(data){
                     if(data=='ok'){
                        $.fn.yiiGridView.update('article-grid');
                        }else{
                        alert('删除失败');
                     }
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
        $('#search').live("click", function() {
            if ($('.search-form').is(':visible') == false) {
                $('.search-form').show(500);
            } else {
                $('.search-form').hide(500);
            }
        });
        $('.search-form form').submit(function() {
            $.fn.yiiGridView.update('article-grid', {
                data: $(this).serialize()
            });
            return false;
        });
    });
</script>