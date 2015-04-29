<style>
    .content{margin: 0 auto; width: 1030px; }
    .content .content-left{width:800px;position: relative; display: inline-block;} 
    .content .sidebar{width:210px; border-width: 2px; border-style: solid;border-top: 0;background-color: #fff; display: inline-block;height: 260px;background-color: #FAFAFA;}
    .content .pic{display: inline-block; position: absolute; left: 210px; top:0;padding: 10px;}
    .content .news{display: inline-block; width: 400px; float: right;}
    .content .news .product-section{padding-bottom: 20px;}
    .star-score{margin: 0;padding: 0;}
    .star-score li{ float: left;width:14px;height: 14px; background: url("/images/icon-start.png") no-repeat -78px top;} 
    .star-score li:first-child, .star-score li.active{ float: left;width:14px;height: 14px; background: url("/images/icon-start.png") no-repeat left top;} 
    .star-score li a{display: inline-block;height: 100%;width: 100%;}
    .star-score li:hover{cursor: pointer;}
    .hot-tags a{color: #fff;}
    .selected-tags-list{ padding-top: 10px; padding-bottom: 10px;}
    .selected-tags-list .label{height: 20px; line-height: 20px;}
    .selected-tags-list .l-title{ float: left;width: 60px;text-align: right;}

    .selected-tags-list .l-right{ float: left; width: 1030px;}
    .selected-tags-list .l-right li{display: inline-block;}
    .selected-tags-list  a{color:#fff;}
    .selected-tags-list  .close-label{color:#fff;}
    .selected-tags-list  .close-label  a{color:#F9F9F9; display:inline-block;padding-left: 4px;}
</style>
<?php
$this->pageTitle = "点评 - " . Yii::app()->name;
$this->widget('bootstrap.widgets.TbBreadcrumbs', array('links' =>  array("点评")));
?>
<div class="content clearfix">

    <div id="setting-tabs" class="tabs-above">
        <fieldset>
            <legend style="margin-bottom:10px;">网站点评</legend> 
        </fieldset>
        <div style="padding-top: 5px;">
            <div class="tabbable">
                <ul class="nav nav-tabs">
                    <li class="<?php echo (!isset($_GET['type']) || ($_GET['type'] == "")) ? "active" : '' ?>">
                        <a href="<?php echo $this->createUrl('comment'); ?>">所有点评</a>
                    </li>
                    <li class="<?php echo ($_GET['type'] == "new") ? "active" : '' ?>">
                        <a href="<?php echo $this->createUrl('comment', array("type" => "new")); ?>">最新点评</a>
                    </li>
                    <li class="<?php echo ($_GET['type'] == "hot") ? "active" : '' ?>">
                        <a href="<?php echo $this->createUrl('comment', array("type" => "hot")); ?>">最热点评</a>
                    </li>
                    <li class="<?php echo $_GET['type'] == 'comment' ? "active" : '' ?>">
                        <a href="<?php echo $this->createUrl('comment', array('type' => 'comment', 'action' => isset($_GET["action"]) ? $_GET["action"] : "create")); ?>">我要写点评</a>
                    </li>
                </ul>
                <div class="right-tab tab-content">
                    <div class="tab-pane active" id="tab1">
                        <?php if (!isset($_GET['type']) || ($_GET['type'] == "")): ?>
                            <?php if ($_GET["tags"] != "") { ?>
                                <div class="selected-tags-list">
                                    <div class="l-title">已选标签：</div>
                                    <div class="r-list">
                                        <?php
                                        $tags_array = explode(",", $_GET["tags"]);
                                        for ($i = 0; $i < count($tags_array); $i++) {
                                            $tags_name = $tags_array[$i];
                                            $new_tags = Article::model()->removeQuery($_GET["tags"], $tags_array[$i]);
                                            $href = $this->createUrl("comment", array("tags" => $new_tags, "score" => $_GET["score"]));
                                            ?>
                                            <span class='label label-info active close-label' style='margin-right:10px;'><?php echo $tags_name; ?><a href="<?php echo $href; ?>" title="关闭">×</a></span>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>
                            <?php if ($_GET["score"] != "") { ?>
                                <div class="selected-tags-list">
                                    <div class="l-title">已选星级：</div>
                                    <?php
//                            if ($_GET["tags"] != "") {
                                    $score_array = explode(",", $_GET["score"]);
                                    for ($i = 0; $i < count($score_array); $i++) {
                                        $score = $score_array[$i];
                                        $new_score = Article::model()->removeQuery($_GET["score"], $score_array[$i]);
                                        $href = $this->createUrl("comment", array("score" => $new_score, "tags" => $_GET["tags"]));
                                        ?>
                                        <span class='label label-info active close-label' style='margin-right:10px;'><?php echo $score . "星"; ?><a href="<?php echo $href; ?>" title="关闭">×</a></span>
                                        <?php
                                    }
                                    ?>
                                </div>
                                <?php
                            }
                            ?>
                            <a name="form"></a>
                            <div id="commentSum" style="width:100%;height:140px;">
                                <div class="pull-left starSum" style="width:200px;height:100%;">
                                    <?php
                                    for ($i = 5; $i > 0; $i--) {
                                        $countScore += ($countArray[$i] * $i);
                                        ?>
                                        <div style="float:left;width:100%">
                                            <div style="float:left;"><?php echo $i . "" . "星"; ?></div>
                                            <div style="float:left;width:100px;margin-left: 10px;margin-right: 10px;">
                                                <?php
                                                $precent = $total != 0 ? (($countArray[$i] / $total) * 100) : 0;
                                                $this->widget('bootstrap.widgets.TbProgress', array(
                                                    // 'type'=>'success', // 'info', 'success' or 'danger'
                                                    'percent' => $precent,
                                                    //'striped'=>true,
                                                    'animated' => true,
                                                    'type' => 'info',
                                                    'htmlOptions' => array('style' => 'margin-bottom:6px;')
                                                ));
                                                ?>
                                            </div>

                                            <div style="float:left">
                                                <a href="<?php echo $this->createUrl("comment", array("score" => $i, "tags" => $_GET["tags"])); ?>" class="<?php echo ($_GET["score"] == $i) ? 'T_total' : ''; ?>"><?php echo "(" . $countArray[$i] . ")"; ?></a>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                    ?></div>
                                <div class="pull-left comment" style="width:300px;">
                                    <div class="hot-tags">
                                        <?php
                                        for ($i = 0; $i < count($tagsArray); $i++) {
                                            $tags = Article::model()->addQuery($_GET["tags"], $tagsArray[$i]);
                                            $count = SysComment::getCountByTagsName($tagsArray[$i]);
                                            if ($tagsArray[$i] == "") {
                                                break;
                                            }
                                            ?>
                                            <span class='label <?php echo in_array($tagsArray[$i], explode(",", $_GET["tags"])) ? "label-info" : "" ?>' style='margin-right:10px;'><?php echo CHtml::link($tagsArray[$i] . "(" . $count . ")", array("comment", "tags" => $tags, "score" => $_GET["score"])); ?></span>
                                            <?php
                                        }
                                        ?> 
                                    </div>
                                    <span class="pull-left star-pic sa<?php echo $total != 0 ? round($countScore / $total) : 5; ?>"></span>(<a href="<?php echo $this->createUrl("comment", array("tags" => $_GET["tags"])); ?>" class="<?php echo!isset($_GET["score"]) ? 'T_total' : ''; ?>"><?php echo $total; ?></a> 条点评)<div>平均<?php echo $total != 0 ? round($countScore / $total) . "星" : "5星"; ?></div>
                                </div>
                            </div>
                            <?php
                            if (count($dataProvider->getData()) == 0) {
                                echo "<div class='alert alert-block alert-info'>暂无点评.</div>";
                            } else {
                                $this->widget('bootstrap.widgets.TbListView', array(
                                    'dataProvider' => $dataProvider,
                                    'itemView' => '../_syscommentlist',
                                    'htmlOptions' => array('style' => 'padding-top:0px')
                                ));
                            }
                            ?>
                        <?php elseif (($_GET['type'] == "comment")): ?>
                            <div class="form">
                                <?php
                                $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
                                    'id' => 'comment-form',
                                    'clientOptions' => array(
                                        'validateOnSubmit' => true,
                                        'validateOnChange' => false,
                                    ),
                                    'enableAjaxValidation' => true,
                                    'type' => 'horizontal',
                                    'action' => $this->createUrl('createsyscomment'),
                                ));
                                $model_comment = new SysComment();
                                ?>
                                <?php echo $form->errorSummary($model_comment); ?>
                                <?php echo $form->hiddenField($model_comment, 'score', array('value' => 1)); ?>

                                <div class="control-group ">
                                    <label class="control-label required">评分<span class="required">*</span></label>
                                    <div class="controls">
                                        <ul class="star-score">
                                            <li class="star-pic"><a title="1分"></a></li>  
                                            <li class="star-pic"><a title="2分"></a></li>  
                                            <li class="star-pic"><a title="3分"></a></li> 
                                            <li class="star-pic"><a title="4分"></a></li>  
                                            <li class="star-pic"><a title="5分"></a></li>
                                        </ul>
                                    </div>  
                                </div>
                                <div class="control-group ">
                                    <label class="control-label required">标签</label>
                                    <div class="controls" id="iconView" >
                                        <?php
                                        $this->widget('bootstrap.widgets.TbSelect2', array(
                                            'asDropDownList' => false,
                                            'name' => 'tags',
                                            'options' => array(
                                                'tags' => SysComment::model()->getTagsArray(Yii::app()->user->id),
                                                //    'placeholder' => '请输入标签',
                                                'width' => '200px',
                                                'tokenSeparators' => array(',', ' ')
                                            )
                                        ));
                                        ?>
                                    </div>  
                                </div>

                                <script>
                                    var cap_max = 200;
                                    function getLeftChars(varField) {
                                        var cap = cap_max;
                                        var leftchars = cap - varField.value.length;
                                        return (leftchars);
                                    }
                                    function onCharsChange(varField) {
                                        var leftChars = getLeftChars(varField);
                                        if (leftChars >= 0)
                                        {
                                            $('#inputChar').text(varField.value.length);
                                            $('#overplusChar').text(leftChars);
                                            return true;
                                        } else {
                                            $('#Comment_content').val(varField.value.substr(0, 200));
                                            alert("字数已经超过");
                                            return false;
                                        }
                                    }
                                </script>
                                <?php echo $form->textAreaRow($model_comment, 'content', array("style" => "width:400px;height:100px;", 'onpaste' => 'onCharsChange(this)', 'onKeyUp' => 'onCharsChange(this)')); ?>
                                <div class="control-group" style="margin-bottom: 10px;">
                                    <label class="control-label"></label>
                                    <div class="controls">
                                        已输入&nbsp;&nbsp;<span id="inputChar" style="font-family: Constantia, Georgia;font-size: 22px;">0</span>&nbsp;&nbsp;字符，剩余&nbsp;&nbsp;<span id="overplusChar" style="font-family: Constantia, Georgia;font-size: 22px;">200</span>
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <?php
                                    $this->widget('bootstrap.widgets.TbButton', array(
                                        'label' => '提交',
                                        'buttonType' => 'button',
                                        'type' => 'info',
                                        'htmlOptions' => array("name" => Yii::app()->user->isGuest ? 'noLogin':'submitComment'),
                                    ));
                                    ?>
                                </div>
                                <?php $this->endWidget(); ?>
                            </div><!-- form -->
                        <?php elseif ($_GET["type"] == "new"): ?>
                            <?php
                            if (count($dataProviderByTime->getData()) == 0) {
                                echo "<div class='alert alert-block alert-info'>暂无最新点评.</div>";
                            } else {
                                $this->widget('bootstrap.widgets.TbListView', array(
                                    'dataProvider' => $dataProviderByTime,
                                    'template' => '{items}',
                                    'itemView' => '../_syscommentlist',
                                    'htmlOptions' => array('style' => 'padding-top:0px')
                                ));
                            }
                            ?>
                        <?php elseif ($_GET["type"] == "hot"): ?>
                            <?php
                            $idString = SysComment::model()->getShowIdStr();
                            if ($idString != "") {

                                $comment = Yii::app()->db->createCommand('select *,count(comment_id) as num  from sys_comment_reply where is_show = 0 and comment_id in  (' . $idString . ')  group by comment_id  order by num desc')->queryAll();
                            } else {
                                $comment = Yii::app()->db->createCommand('select *,count(comment_id) as num  from sys_comment_reply where is_show = 0 and comment_id=0  group by comment_id  order by num desc')->queryAll();
                            }

                            $i = 0;
                            $count = array();
                            if (empty($comment)) {
                                echo "<div class='alert alert-block alert-info'>暂无最热点评.</div>";
                            } else {
                                foreach ($comment as $name) {
                                    $i++;
                                    $model = SysComment::model()->findByPk($name['comment_id']);
                                    if ($i == 6) {
                                        break;
                                    }
                                    ?>
                                    <?php $this->renderPartial('../_syscommentlist', array("data" => $model)); ?>
                                <?php
                                }
                            }
                            ?>
                        <?php endif; ?>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var createSysReplyUrl = '<?php echo $this->createUrl("createsysreply"); ?>';
    var deleteCommentUrl = '<?php echo $this->createUrl("deletesyscomment"); ?>';
    var deleteReplyUrl = '<?php echo $this->createUrl("deletesysreply"); ?>';
    var typeParams = '<?php echo $_GET["type"]; ?>';
</script>
<script>
    $(function() {
        $(".noLogin").live("click", function() {
            alert("请先登录");
        });
        $("#createComment").toggle(function() {
            $("#comment-box").show(500);
        }, function() {
            $("#comment-box").hide(500);
        });
        $("[name='submitComment']").live("click",function(){
            $("#comment-form").submit();
        })
        $("#create-tags").live("click", function() {
            $(".fore2").show();
            $(this).hide();
        });
        $(".star-score li").live("click", function() {
            $(this).addClass("active");
            $(this).prevAll().addClass("active");
            $(this).nextAll().removeClass("active");
            $("#SysComment_score").val($(this).index() + 1);
        });
        $(document).delegate(".comment-reply", 'mouseenter', function() {
            $(this).find("a[data-name='reply-comment']").show();
            $(this).find("a[data-name='delete-reply']").show();
        });
        $(document).delegate(".comment-reply", 'mouseleave', function() {
            $(this).find("a[data-name='reply-comment']").hide();
            $(this).find("a[data-name='delete-reply']").hide();
        });
        $(document).delegate("a[data-name='delete-comment']", 'click', function() {
            if (window.confirm("删除评论时，此评论下的回复也会全部删除，确定要删除所选的评论吗?")) {
                var id = $(this).attr("data-value");
                var self = $(this);
                $.ajax({
                    url: deleteCommentUrl,
                    data: {'id': id},
                    type: "POST",
                    dataType: "html",
                    success: function(data) {
                        if (data == "ok") {
                            alert("删除成功");
                            self.parents(".list").remove();
                        } else {
                            alert("删除失败");
                        }
                    }
                });
                return false;
            } else {
                return false;
            }
        });
        $(document).delegate("a[data-name='delete-reply']", 'click', function() {
            if (window.confirm("确定要删除所选的回复吗?")) {
                var id = $(this).attr("data-value");
                var self = $(this);
                $.ajax({
                    url: deleteReplyUrl,
                    data: {'id': id},
                    type: "POST",
                    dataType: "html",
                    success: function(data) {
                        if (data == "ok") {
                            alert("删除成功");
                            self.parents(".comment-reply").remove();
                        } else {
                            alert("删除失败");
                        }
                    }

                });
                return false;
            } else {
                return false;
            }
        });
        $(document).delegate("a[data-name='reply-comment']", 'click', function() {
            var userId = $(this).attr("user-value");
            var commentId = $(this).attr("data-value");
            var userName = $(this).attr("name-value");
            var page = $(this).attr("data-page");
            if ($("#createDiaryReply").length > 0) {
                $("#createDiaryReply").remove();
            }
            ;
            form = $("<form id='createDiaryReply' style='position:relative;right:0;padding:10px;' class='form well'></form>");
            form.attr("action", createSysReplyUrl);
            form.attr("method", "POST");
            user = $("<div style='font-size:14px;'><b>@ " + userName + "</b> :</div>");
            form.append(user);
            userIuput = $("<input type='hidden' name='user_id'/>");
            userIuput.attr("value", userId);
            form.append(userIuput);
            commentInput = $("<input type='hidden' name='comment_id'/>");
            commentInput.attr("value", commentId);
            form.append(commentInput);
            commentType = $("<input type='hidden' name='type' value='" + typeParams + "' />");
            form.append(commentType);
            content = $("<input name='content' class='content' style='width:86%;margin-bottom:0;' type='text'/>");
            form.append(content);
            pageInput = $("<input type='hidden' name='page'/>");
            pageInput.attr("value", page);
            form.append(pageInput);
            submit = $('<button class="btn" type="button" id="createDiarySubmit" style="margin-left:10px;">发表</button>');
            form.append(submit);
            form.insertAfter($(this).parent());
            return false;
        });
        $(document).delegate("#createDiarySubmit", 'click', function() {
            if ($.trim($(this).parents("#createDiaryReply").find(".content").val()) == "") {
                alert("请输入回复内容");
            } else {
                $("#createDiaryReply").submit();
            }
        });
    });
</script>