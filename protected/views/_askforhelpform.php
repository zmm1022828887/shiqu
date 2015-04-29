<div class="form" style="padding-top:10px;">
    <?php
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => 'request-form',
        'action' => $this->createUrl("request"),
    ));
    ?>
    <?php
    $model = new Request();
    echo $form->textField($model, 'user_name', array("id" => "askUser", "placeholder" => "搜索您想邀请的用户", "class" => "pull-left"));
    echo $form->hiddenField($model, 'to_user');
    echo $form->hiddenField($model, 'question_id', array('value' => $question->id));
    ?>
    <?php
    $requestion = Request::model()->findAll("question_id=:question_id and create_user=:create_user and delete_flag!=1", array(":question_id" => $question->id, ":create_user" => Yii::app()->user->id), array('order' => 'create_time desc'));
    $i = 0;
    ?>
    <?php if (!empty($requestion)) { ?>
        <div class="pull-left" style="height: 30px;line-height: 30px;margin-left: 4px;">
            您已经邀请
            <?php
            foreach ($requestion as $key => $value) {
                $i++;
                ?>
                <a class="user-label" href="javascript:;" data-id="<?php echo $value->to_user; ?>"><?php echo User::getNameById($value->to_user); ?></a>
                <?php echo $i != count($requestion) ? "、" : ""; ?>
        <?php } ?>
        </div>
    <?php } ?>
<?php $this->endWidget(); ?>
</div><!-- form -->
<script>
    $(document).ready(function() {
        $("#askUser").autocomplete("<?php echo $this->createUrl('/default/search') ?>", {
            width: 220,
            max: 12,
            highlight: false,
            scroll: true,
            scrollHeight: 300,
            formatItem: function(data, i, n, value) {
                var obj = eval("(" + data + ")");
                if (obj.returnValue == true) {
                    return "<div class='options'><img src='" + obj.url + "' width='25' height='25' style='margin: 2px 10px 0 0;float:left'><div style='float:left;height:25px;line-height:25px;'>" + obj.user_name + "</div><br /></div>";
                } else {
                    return "<div class='title'><div style='float:left'>" + obj.message + "</div></div>";
                }
            },
            formatResult: function(data, value) {
                var obj = eval("(" + data + ")");
                return  obj.user_name;
            }
        }).result(function(event, item) {
            var obj = eval("(" + item + ")");
            $("#Request_to_user").val(obj.user_id);
        });
    });
</script>