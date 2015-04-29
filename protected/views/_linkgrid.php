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
<?php if ($this->getAction()->getId() != "index") { ?> 
    <div id="search-topic" class="search-form" style="display: none;">
        <?php
        $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
            'action' => Yii::app()->createUrl($this->route),
            'method' => 'get',
            'type' => 'horizontal',
        ));
        $model = new Link();
        ?>
        <div class="search_form" align="center">

            <div class="row">
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
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'link',
        'label' => '新建',
        'icon' => 'icon-pencil-2',
        'type' => 'info',
        'url' => $this->createUrl("createlink"),
        'htmlOptions' => array("style" => "margin-left:5px;")
    ));
    ?>
<?php } ?>
<?php
$criteria = new CDbCriteria;
$criteria->order = "no";
$model = $_GET["Link"];
if ($model['name']) {
    $criteria->addSearchCondition('name', trim($model['name']));
}
$dataProvider = new CActiveDataProvider('Link', array(
    'criteria' => $criteria,
        ));
$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'link-grid',
    'type' => 'striped',
    'template' => '{items}{pager}',
    'rowHtmlOptionsExpression' => 'array("article-id"=>$data->id)',
    'dataProvider' => $dataProvider,
    'columns' => array(
        array(
            'name' => 'no',
            'headerHtmlOptions' => array('style' => 'width: 100px'),
        ),
        array(
            'name' => 'name',
            'headerHtmlOptions' => array('style' => 'width: 200px'),
        ),
        array(
            'name' => 'status',
            'type' => 'raw',
            'value' => '$data->status==1 ? Chtml::tag("span", array("class"=>"badge badge-success"), Chtml::tag("i", array("class"=>"icon-checkmark-3","data-original-title"=>"显示","rel"=>"tooltip"), "")) : Chtml::tag("span", array("class"=>"badge"), Chtml::tag("i", array("class"=>"icon-close-2","data-original-title"=>"隐藏","rel"=>"tooltip"), ""))',
            'headerHtmlOptions' => array('style' => 'width:30px;')
        ),
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'header' => '操作',
            'template' => '{showlink}{hidelink}{updatelink}{delete}',
            'headerHtmlOptions' => array('style' => 'width:60px;text-align:center;'),
            'buttons' => array(
                'showlink' => array(
                    'icon' => 'icon-checkmark-2',
                    'label' => '显示',
                    'visible' => '($data->status==0) ? true : false',
                    //   'visible' => '((Yii::app()->user->name == "admin" || $data->create_user== Yii::app()->user->id) && ($data->publish=="1")) ? false: true',
                    'url' => 'Yii::app()->controller->createUrl("changelink",array("id"=>$data->primaryKey))',
                    'options' => array(
                        'style' => 'margin-right:5px;',
                        'title' => '显示此友情链接',
                    ),
                    'click' => "function(){
                                    $.fn.yiiGridView.update('link-grid', {
                                        type:'GET',
                                        url:$(this).attr('href'),
                                        success:function(data) {
                                             $.fn.yiiGridView.update('link-grid');
                                        }
                                    })
                                    return false;
                              }
                     ",
                ),
                'delete' => array(
                    'url' => 'Yii::app()->controller->createUrl("deletelink",array("id"=> $data->id))',
                ),
                'hidelink' => array(
                    'label' => '隐藏',
                    'icon' => 'icon-close-2',
                    'visible' => '($data->status==1) ? true : false',
                    'url' => 'Yii::app()->controller->createUrl("changelink",array("id"=> $data->id))',
                    'options' => array(
                        'style' => 'margin-right:5px;',
//                        'class' => 'icon-eye-2',
                        'title' => '隐藏此友情链接',
                    ),
                    'click' => "function(){
                                    $.fn.yiiGridView.update('link-grid', {
                                        type:'GET',
                                        url:$(this).attr('href'),
                                        success:function(data) {
                                             $.fn.yiiGridView.update('link-grid');
                                        }
                                    })
                                    return false;
                              }
                     ",
                ),
                'updatelink' => array(
                    'label' => '',
                    'url' => 'Yii::app()->controller->createUrl("updatelink",array("id"=> $data->id))',
                    'options' => array(
                        'style' => 'margin-right:5px;cursor:pointer',
                        'class' => 'icon-pencil',
                        'title' => '修改友情链接',
                    ),
                )
            ),
        ),
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
            $.fn.yiiGridView.update('desktop-setting-grid', {
                data: $(this).serialize()
            });
            return false;
        });
    });
</script>