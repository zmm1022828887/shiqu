<?php if (!empty($successMessage)): ?>
    <div class="alert alert-block alert-success">
        <?php
        for ($i = 0; $i < count($successMessage); $i++) {
            echo $successMessage[$i];
        };
        ?>
        <a class="close" data-dismiss="alert" title="关闭">×</a>
    </div>
<?php endif; ?>
<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'tags-form',
    'type' => 'horizontal',
    'htmlOptions' => array('style' => 'margin-top:30px;'),
        ));
?>
<?php if ($model->identity != "") { ?>
    <div class="control-group ">
        <label class="control-label">身份</label>
        <div class="controls">
            <?php
            $identityArray = explode(",", $model->identity);
            for ($i = 0; $i < count($identityArray); $i++) {
                ?>
                <span class="btn btn-small" data-name="sysTags"> <?php echo $identityArray[$i]; ?></span>
            <?php } ?>
        </div>
    </div>
<?php } ?>
<?php if ($model->profession != "") { ?>
    <div class="control-group ">
        <label class="control-label">职位</label>
        <div class="controls">
            <?php
            $professionArray = explode(",", $model->profession);
            for ($i = 0; $i < count($professionArray); $i++) {
                ?>
                <span class="btn btn-small"  data-name="sysTags"><?php echo $professionArray[$i]; ?></span>
            <?php } ?>
        </div>
    </div>
<?php } ?>
<?php if ($model->hobbies != "") { ?>
    <div class="control-group ">
        <label class="control-label">爱好</label>
        <div class="controls">
            <?php
            $hobbiesArray = explode(",", $model->hobbies);
            for ($i = 0; $i < count($hobbiesArray); $i++) {
                ?>
                <span class="btn btn-small" data-name="sysTags"><?php echo $hobbiesArray[$i]; ?></span>
            <?php } ?>
        </div>
    </div>
<?php } ?>
<?php
echo $form->select2Row($userModel, 'tags', array('asDropDownList' => false, 'class' => 'span10', 'options' => array('tags' => array(), 'tokenSeparators' => array(',', ' '), 'maximumSelectionSize'=>5)));
?>
<?php
$this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit', 'label' => '保存', 'type' => 'info', 'htmlOptions' => array('style' => 'margin-left:180px;')));
$this->endWidget();
?>
<script>
    $(document).ready(function() {
        $("[data-name='sysTags']").click(function() {
            var title = $(this).text();
            var tagsVal = $("#User_tags").val();
            if (tagsVal.indexOf(title) >= 0)
            {
                alert('此标签已经存在');
            } else if (tagsVal.split(",").length==5) {
                 alert('每个人最多可拥有5个个人标签');
            } else {
                var li='<li class="select2-search-choice"><div title="' + title + '">' + title + '</div><a href="#"  class="select2-search-choice-close" tabindex="-1"></a></li>';
              
            $(".select2-choices").append(li);
               $(".select2-choices .select2-search-field").remove();
                $("#User_tags").val(tagsVal + "," + title);
            }
        });
         $(document).delegate(".select2-search-choice-close", 'click', function() {
            $(this).parent().remove();
        })
    });
</script>