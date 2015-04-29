<style>
    #search-user .row{
        margin-top:5px;
    }
    #search-user  span.required{display: none;}
    #search-user .span6{width: 400px;}
    #search-user .form-horizontal .control-label{
        width: 80px;
    }
    #search-user  .form-horizontal .controls {
        margin-left: 80px;
    }    
</style>
<?php
if ($action == "all") {
    ?>
    <div id="search-user" class="search-form" style="display: none;">
        <div class="search_form" align="center">
            <?php
            $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
                'action' => Yii::app()->createUrl($this->route),
                'method' => 'get',
                'type' => 'horizontal',
                    ));
            $model = new User();
            $model->gender = "";
            ?>
            <div class="row">
                <div class="span6">
                    <?php
                    echo $form->dateRangeRow($model, 'register_time', array('prepend' => '<i class="icon-calendar"></i>',
                        'options' => array('format' => 'yyyy/MM/dd', 'callback' => 'js:function(start, end){console.log(start.toString("MMMM d, yyyy") + " - " + end.toString("MMMM d, yyyy"));}'),
                    ));
                    ?></div>
                <div class="span6">
                    <?php echo $form->textFieldRow($model, 'user_name', array('maxlength' => 100)); ?>	
                </div>
            </div>
            <div class="row">
                <div class="span6">
                    <?php echo $form->dropdownlistRow($model, 'gender', array('' => '', '0' => '女', '1' => '男')); ?></div>
                <div class="span6">
                    <?php echo $form->textFieldRow($model, 'chinese_name', array('maxlength' => 100)); ?>	
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
}
?>
<?php
$columns = array(
    array('type' => 'raw', 'name' => 'user_name', 'value' => 'CHtml::openTag("img", array("src" =>Yii::app()->controller->createUrl("getimage",array("id"=>$data->id,"type"=>"avatar")),"style"=>"margin-right:4px;height:30px;width:30px;")).CHtml::link($data->user_name,array("userinfo","user_id"=>$data->id),array("target"=>"_blank","data-id"=>$data->id,"class"=>"user-label"))',),
    array('header' => '中文名', 'name' => 'chinese_name', 'value' => '$data->chinese_name'),
    array('name' => 'gender', 'value' => '$data->gender==1?"男":"女"'),
    array('name' => 'last_visit_time', 'value' => '$data->last_visit_time==""?"":date("Y-m-d H:i:s",$data->last_visit_time)'),
    array('header' => '注册时间', 'name' => 'register_time', 'value' => 'date("Y-m-d H:i:s",$data->register_time)'),
    array('header' => '是否允许登陆', 'name' => 'not_login', 'value' => '$data->not_login=="0"?"允许":"不允许"'),
    array(
        'class' => 'bootstrap.widgets.TbButtonColumn',
        'header' => '操作',
        'template' => '{update}{login}{nologin}{init}{delete}{offline}',
        'headerHtmlOptions' => array('style' => 'width:160px;text-align:center;'),
        'buttons' => array(
            'update' => array(
                'url' => 'Yii::app()->controller->createUrl("updateuser",array("id"=>$data->primaryKey))',
                'options' => array(
                    'style' => 'margin-left:5px;'
                )
            ),
            'login' => array(
                'icon' => 'icon-checkmark-2',
                'label' => '允许登陆',
                'visible' => '$data->not_login=="1" ? true: false',
                'url' => 'Yii::app()->controller->createUrl("changelogin",array("id"=>$data->primaryKey))',
                'options' => array(
                    'style' => 'margin-left:5px;'
                ),
                'click' => "function(){
                                    $.fn.yiiGridView.update('user', {
                                        type:'GET',
                                        url:$(this).attr('href'),
                                        success:function(data) {
                                              $.fn.yiiGridView.update('user');
                                        }
                                    })
                                    return false;
                              }
                     ",
            ),
            'nologin' => array(
                'icon' => 'icon-close-2',
                'label' => '禁止登陆',
                'visible' => '$data->not_login=="0" ? true: false',
                'url' => 'Yii::app()->controller->createUrl("changelogin",array("id"=>$data->primaryKey))',
                'options' => array(
                    'style' => 'margin-left:5px;'
                ),
                'click' => "function(){
                                    $.fn.yiiGridView.update('user', {
                                        type:'GET',
                                        url:$(this).attr('href'),
                                        success:function(data) {
                                          if(data=='ok'){
                                              $.fn.yiiGridView.update('user');
                                            }else{
                                              alert('设置失败');
                                             }
                                        }
                                    })
                                    return false;
                              }
                     ",
            ),
            'init' => array(
                    'url' => '',
                    'label' => '重置密码',
                    'icon' => 'icon-filter-3',
                    'options' => array('style' => 'margin-left:5px;cursor:pointer', 'op' => 'reset')
            ),
            'delete' => array(
                'label' => '删除',
                'url' => 'Yii::app()->controller->createUrl("deleteuser",array("id"=>$data->primaryKey))',
                'options' => array(
                    'style' => 'margin-left:5px;'
                ),
                         'click' => "function(){
                if(window.confirm('确定要删除此用户吗?')){
                $.ajax({  
                    url:$(this).attr('href'),
                    type:'POST',   
                    success:function(data) {
                                              $.fn.yiiGridView.update('user');
                                        }
                });
                return false;  
            }
               return false;
            }",
            ),
            'offline'=> array(
                 'icon' => 'icon-clock',
                'label' => '离线',
                'visible'=>'UserOnline::model()->count("id=".$data->primaryKey)>0 ? true : false',
                'url' => 'Yii::app()->controller->createUrl("offlineuser",array("id"=>$data->primaryKey))',
                'options' => array(
                    'style' => 'margin-left:5px;'
                ),
                         'click' => "function(){
                if(window.confirm('确定要使得该用户强制离线吗?')){
                $.ajax({  
                    url:$(this).attr('href'),
                    type:'POST',   
                    success:function(data) {
                                              $.fn.yiiGridView.update('user');
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
    'id' => 'user',
    'type' => 'striped',
    'rowHtmlOptionsExpression' => 'array("data-user-id"=>$data->id,"data-user-name"=>$data->user_name)',
    'template' => $action == "all" ? '{summary}{items}{pager}' : '{items}',
    'dataProvider' => User::model()->getUserList($action),
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
            $.fn.yiiGridView.update('user', {
                data: $(this).serialize()
            });
            return false;
        });
        $(document).delegate("#user a[op='reset']","click",function(){
             var trObj = $(this).parents('tr');
            var userName = trObj.attr('data-user-name');
            var id = trObj.attr('data-user-id');
            $('#initUserId').val(id);
            $('#initUsername').val(userName);
            $('#initPassword').val('');
            $('#resetpassword').modal('show');
            return false;
        });
        $("#resetpassword").on('shown', function() {
            $('#initPassword').val('');
            $(this).find(".control-group").removeClass("success");
            $(this).find(".control-group").removeClass("error");
            $(this).find(".help-inline").hide().removeClass("error");
        });
        $("#initSumbit").live("click",function() {
            var $forms = $("#init-user-form");
            $.ajax({
                url: $forms.attr("action"),
                type: 'post',
                data: $forms.serialize(),
                dataType: 'json',
                success: function(data) {
                    if ((data.password)) {
                        $.each(data, function(k, v) {
                            if ($('#User_' + k).parents('.control-group').find('.error').length > 0) {
                                $('#User_' + k).parents('.control-group').find('.error').remove();
                                $('#User_' + k).parents('.control-group').removeClass('error');
                            }
                            $("#User_password_em_").text(v).show().parents(".control-group").addClass('error');
                        });
                    } else {
                       $.fn.yiiGridView.update('user');
                       $('#resetpassword').modal('hide');
                    }
                }
            });
        });
    });
</script>