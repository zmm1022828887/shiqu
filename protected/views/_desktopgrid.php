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
        $model = new DesktopSetting();
        ?>
        <div class="search_form" align="center">

            <div class="row">
                <div class="span6">
                    <?php echo $form->textFieldRow($model, 'app_name'); ?></div>
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
        'url' => $this->createUrl("createsetting"),
        'htmlOptions' => array("style" => "margin-left:5px;")
    ));
    ?>
<?php } ?>
<?php
$criteria = new CDbCriteria;
$criteria->order = "app_no";
$model = $_GET["DesktopSetting"];
if ($model['app_name']) {
    $criteria->addSearchCondition('app_name', trim($model['app_name']));
}
$dataProvider = new CActiveDataProvider('DesktopSetting', array(
    'criteria' => $criteria,
        ));
$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'desktop-setting-grid',
    'type' => 'striped',
    'template' => '{items}{pager}',
    'rowHtmlOptionsExpression' => 'array("article-id"=>$data->id)',
    'dataProvider' => $dataProvider,
    'columns' => array(
        array(
            'name' => 'app_no',
            'headerHtmlOptions' => array('style' => 'width: 100px'),
        ),
        array(
            'name' => 'app_name',
            'headerHtmlOptions' => array('style' => 'width: 200px'),
        ),
        array(
            'name' => 'app_id',
            'headerHtmlOptions' => array('style' => 'width: 200px'),
        ),
        array(
            'name' => 'app_length',
            'headerHtmlOptions' => array('style' => 'width: 100px'),
        ),
        array(
            'name' => 'app_status',
            'type' => 'raw',
            'value' => '$data->app_status==1 ? Chtml::tag("span", array("class"=>"badge badge-success"), Chtml::tag("i", array("class"=>"icon-checkmark-3","data-original-title"=>"显示","rel"=>"tooltip"), "")) : Chtml::tag("span", array("class"=>"badge"), Chtml::tag("i", array("class"=>"icon-close-2","data-original-title"=>"隐藏","rel"=>"tooltip"), ""))',
            'headerHtmlOptions' => array('style' => 'width:30px;')
        ),
        array(
            'name' => 'app_direction',
            'type' => 'raw',
            'value' => '$data->app_direction=="r" ? "右边":"左边"',
            'headerHtmlOptions' => array('style' => 'width:30px;')
        ),
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'header' => '操作',
            'template' => '{showdesktop}{hidedesktop}{updatearticle}{delete}',
            'headerHtmlOptions' => array('style' => 'width:60px;text-align:center;'),
            'buttons' => array(
                'showdesktop' => array(
                    'icon' => 'icon-checkmark-2',
                    'label' => '显示',
                    'visible' => '($data->app_status==0) ? true : false',
                    //   'visible' => '((Yii::app()->user->name == "admin" || $data->create_user== Yii::app()->user->id) && ($data->publish=="1")) ? false: true',
                    'url' => 'Yii::app()->controller->createUrl("changedesktop",array("id"=>$data->primaryKey))',
                    'options' => array(
                        'style' => 'margin-right:5px;',
                        'title' => '显示此模块',
                    ),
                    'click' => "function(){
                                    $.fn.yiiGridView.update('desktop-setting-grid', {
                                        type:'GET',
                                        url:$(this).attr('href'),
                                        success:function(data) {
                                             $.fn.yiiGridView.update('desktop-setting-grid');
                                        }
                                    })
                                    return false;
                              }
                     ",
                ),
                'delete' => array(
                    'url' => 'Yii::app()->controller->createUrl("deletedesktop",array("id"=> $data->id))',
                ),
                'hidedesktop' => array(
                    'label' => '隐藏',
                    'icon' => 'icon-close-2',
                    'visible' => '($data->app_status==1) ? true : false',
                    'url' => 'Yii::app()->controller->createUrl("changedesktop",array("id"=> $data->id))',
                    'options' => array(
                        'style' => 'margin-right:5px;',
//                        'class' => 'icon-eye-2',
                        'title' => '隐藏此模块',
                    ),
                    'click' => "function(){
                                    $.fn.yiiGridView.update('desktop-setting-grid', {
                                        type:'GET',
                                        url:$(this).attr('href'),
                                        success:function(data) {
                                             $.fn.yiiGridView.update('desktop-setting-grid');
                                        }
                                    })
                                    return false;
                              }
                     ",
                ),
                'updatearticle' => array(
                    'label' => '',
                    'url' => 'Yii::app()->controller->createUrl("updatedesktop",array("id"=> $data->id))',
                    'options' => array(
                        'style' => 'margin-right:5px;cursor:pointer',
                        'class' => 'icon-pencil',
                        'title' => '修改模块',
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