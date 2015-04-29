<script type="text/javascript">
    $(document).ready(function() {
        $("#fileProgressContainer_1").css('display', 'none');
        //上传预览
        $("#User_avatar").change(function() {
            var obj = document.getElementById("img");
            $("#ytfileimg").val($(this).val());
            //$("#fileimg").val($(this).val());
            if (window.navigator.userAgent.indexOf("MSIE") >= 1) {
                var browser = navigator.appName;
                var b_version = navigator.appVersion;
                var version = b_version.split(";");
                var trim_Version = version[1].replace(/[ ]/g, "");
                onUploadImgChange(this, 'img', 'tp12', 'tp13');//适应所有IE

            } else {//firefox、opera、 chrome 支持, safari不支持 禁止预览本地图片
                var file = this.files[0];
                var reader = new FileReader();
                reader.onload = function(e) {
                    obj.setAttribute("src", e.target.result)
                }
                reader.readAsDataURL(file);
            }
        });
    });

    //以下代码控制图片预览 适应IE6、ie7、ie8、ie9
    function onUploadImgChange(sender, tp1, tp2, tp3) {
        var objPreview = document.getElementById(tp1);   //'preview'
        var objPreviewFake = document.getElementById(tp2);  // 'preview_fake'
        var objPreviewSizeFake = document.getElementById(tp3);//'preview_size_fake'
        if (objPreviewFake.filters) {
            // IE7,IE8 在设置本地图片地址为 img.src 时出现莫名其妙的后果   
            //（相同环境有时能显示，有时不显示），因此只能用滤镜来解决   
            // IE7, IE8因安全性问题已无法直接通过 input[file].value 获取完整的文件路径   
            sender.select();
            sender.blur();
            var imgSrc = document.selection.createRange().text;

            objPreviewFake.filters.item('DXImageTransform.Microsoft.AlphaImageLoader').src = imgSrc;
            objPreviewSizeFake.filters.item('DXImageTransform.Microsoft.AlphaImageLoader').src = imgSrc;
            autoSizePreview(objPreviewFake, objPreviewSizeFake.offsetWidth, objPreviewSizeFake.offsetHeight);
            objPreview.style.display = 'none';
        }
    }
    function autoSizePreview(objPre, originalWidth, originalHeight) {
        var zoomParam = clacImgZoomParam(120, 120, originalWidth, originalHeight);
        objPre.style.width = zoomParam.width + 'px';
        objPre.style.height = zoomParam.height + 'px';
        objPre.style.marginTop = zoomParam.top + 'px';
        objPre.style.marginLeft = zoomParam.left + 'px';
    }
    function clacImgZoomParam(maxWidth, maxHeight, width, height) {
        var param = {width: width, height: height, top: 0, left: 0};
        param.width = maxWidth;
        param.height = maxHeight;
        param.left = 0;
        param.top = 0;
        return param;
    }
</script>
<style type="text/css">
    /* 以下代码控制图片预览 */   
    .yl_kk{width:120px;height:120px;overflow: hidden;border: 1px solid #CCCCCC;}
    .yl_1{width:120px;height:120px;text-align:left;overflow: hidden;}
    /* 该对象用于在IE下显示预览图片 */   
    .yl_2{filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod=scale);width:120px;overflow: hidden;}
    /* 该对象只用来在IE下获得图片的原始尺寸，无其它用途 */   
    .yl_3{filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod=image);  width:120px; visibility:hidden; overflow: hidden;  }   
    /* 该对象用于在FF下显示预览图片 */
    .yl_4{width:120px;height:120px;text-align:left;overflow: hidden;}
</style>
<?php if (!empty($successMessage)): ?>
    <div class="alert alert-block alert-success">
        <?php
        for ($i = 0; $i < count($successMessage); $i++) {
            echo "<p>" . $successMessage[$i] . "</p>";
        };
        ?>
    </div>
<?php endif; ?>
<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'register-form',
    'type' => 'horizontal',
    'action' => $this->getAction()->getId() == "updateuser" ? $this->createUrl("updateuser", array("id" => $_GET["id"])) : $this->createUrl("registeruser"),
    'clientOptions' => array(
        'validateOnSubmit' => true,
        'validateOnChange' => false,
    ),
    'enableAjaxValidation' => $this->getAction()->getId() == "updateuser" ? false : true,
    'method' => 'post',
    'htmlOptions' => array('enctype' => 'multipart/form-data'),
        ));
?>
<?php echo $form->errorSummary($userModel); ?>

<?php if ($this->getAction()->getId() != "updateuser") { ?>
    <?php echo $form->textFieldRow($userModel, 'user_name'); ?>
    <?php echo $form->passwordFieldRow($userModel, 'password'); ?>
    <?php echo $form->passwordFieldRow($userModel, 'retype_password'); ?>
<?php } else { ?>
    <?php echo $form->textFieldRow($userModel, 'user_name', array('readOnly' => true)); ?>
<?php } ?>
<?php echo $form->textFieldRow($userModel, 'chinese_name'); ?>
<?php echo $form->textFieldRow($userModel, 'signature'); ?>
<?php echo $form->textAreaRow($userModel, 'desc'); ?>
<?php echo $form->dropDownListRow($userModel, 'gender', array('1' => '男', '0' => '女')); ?>
<?php if ($userModel->avatar != "") { ?>
    <div class="control-group ">
        <label class="control-label required">已上传的头像</label>
        <div class="controls" >
            <?php
            echo CHtml::image($this->createUrl("getimage", array("id" => $userModel->id, "type" => "avatar")), '图片的说明', array('width' => '100px', 'height' => '100px', 'class' => "avatar"));
            ?>
        </div>  
    </div>  
<?php } ?>
<?php echo $form->fileFieldRow($userModel, 'avatar'); ?>
<div class="control-group ">
    <div class="controls" >
        <div class="yl_kk" id="kk1">
            <div class="yl_1" id="tp14">
                <div class="yl_2" id="tp12">
                    <img class="yl_4" id="img" src="" />
                </div>
            </div>
            <img class="yl_3" id="tp13" />
        </div>
    </div>
</div>
<?php
echo $form->toggleButtonRow($model, 'topic_status', array(
    'options' => array(
        'enabledLabel' => '允许',
        'disabledLabel' => '不允许',
        'enabledStyle' => 'success',
        'disabledStyle' => 'danger',
        'onChange' => 'js:function($el, status, e){ $("#weather_body").toggle(status)}'
    ),
    'labelOptions' => array('style' => 'text-align:left;')
));
?>
<div id="weather_body" style="display:<?php echo $model->topic_status == 1 ? '' : 'none'; ?>">
    <?php
    echo $form->toggleButtonRow($model, 'topic_ids', array(
        'options' => array(
            'enabledLabel' => '允许',
            'disabledLabel' => '不允许',
            'enabledStyle' => 'success',
            'disabledStyle' => 'danger',
        ),
        'checked' => $params['allow_weather_change'] == 1 ? 'checked' : '',
        'labelOptions' => array('style' => 'text-align:left;')
    ));
    ?>
    <div class="control-group" >
        <label class="control-label required" style="text-align:left;">默认城市</label>
        <div class="controls"  >
            <select id="w_province" class="SmallSelect" onChange="InitCity(this.value);" style="margin-right: 10px;"></select>
            <select id="w_city" class="SmallSelect" onChange="InitCounty(this.value);"  style="margin-right: 10px;"></select>
            <select id="w_county" class="SmallSelect" name="weather_city"></select>
        </div>  
    </div>
</div>
<div class="controls">
    <?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'submit',
        'type' => 'info',
        'label' => '保存',
    ));
    ?>
</div>
<?php
$this->endWidget();
?>