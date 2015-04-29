<div class="form" style="padding-top:10px;">
    <?php
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => 'message-form',
        'type' => 'horizontal',
        'action' => $action,
    ));
    ?>
    <?php
    echo $form->textFieldRow($model, 'user_name', array("class" => "searchUser","placeholder"=>"搜索用户"));
    echo $form->hiddenField($model, 'to_uid');
    echo $form->textAreaRow($model, 'content', array("style" => "width:400px;height:100px;padding:4px;resize:none"));
    ?>
    <?php $this->endWidget(); ?>
</div><!-- form -->
<script>
    $(document).ready(function() {
        $(".searchUser").autocomplete("<?php echo $this->createUrl('/default/search') ?>", {
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
            $("#Message_to_uid").val(obj.user_id);
        });
        $("#submit").live('click', function() {
            if ($("#Message_content").val() == "") {
                alert("请输入相应的内容");
            } else {
                $("#message-form").submit();
            }
        });
    });
</script>