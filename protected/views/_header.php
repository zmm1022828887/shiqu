<script>

    $(document).ready(function() {
        $("#searchInput").autocomplete("<?php echo $this->createUrl('/default/searchall') ?>", {
            width: 328,
            max: 12,
            highlight: false,
            scroll: true,
            scrollHeight: 300,
            formatItem: function(data, i, n, value) {
                var obj = eval("(" + data + ")");
                if (obj.returnValue == true) {
                    if (obj.type == "user")
                        return "<div class='options'><img src='" + obj.url + "' width='25' height='25' style='margin: 2px 10px 0 0;float:left'><div style='float:left;height:25px;line-height:25px;'>" + obj.message + "</div><br /></div>";
//                    if (obj.type == "group")
//                        return "<div class='options'><img src='" + obj.url + "' width='25' height='25' style='margin: 2px 10px 0 0;float:left'><div style='float:left;height:25px;line-height:25px;font-weight:bold'>" + obj.message + "</div><div style='float:left;' class='tip'>" + obj.tip + "</div></div>";
                    if (obj.type == "topic")
                        return "<div class='options'><div style='float:left;' class='message'>" + obj.message + "</div><div style='float:left;' class='tip'>" + obj.tip + "</div></div>";
                    if (obj.type == "diary")
                        return "<div class='options'><div style='float:left;' class='message'>" + obj.message + "</div><div style='float:left;' class='tip'>" + obj.tip + "</div></div>";
                    if (obj.type == "all")
                        return "<div class='title' style='width:100%;text-align:center;cursor:pointer'>" + obj.message + "</div>";
                } else {
                    return "<div class='title'><div style='float:left'>" + obj.message + "</div></div>";
                }

            },
            formatResult: function(data, value) {
                var obj = eval("(" + data + ")");
                return  obj.message;
            }
        }).result(function(event, item) {
            var obj = eval("(" + item + ")");
            if ((obj.returnValue == true) || (obj.returnValue == false && obj.type == "all")) {
                location.href = obj.href;
            }
        });
        $("[data-name=noLogin]").live("click", function() {
            if (confirm("请先登录")) {
                $('#loginModal').modal('show');
            } else {
                return false;
            }
            return false;
        });
        $("#noLogin").live("click", function() {
            alert("请先登录");
            return false;
        });
        $("#loginInfo").parent().on({
            "mouseover": function() {
                $("#loginInfo").next().show();
                //   $(this).addClass("active");
            },
            "mouseleave": function() {
                $("#loginInfo").next().hide();
                //   $(this).removeClass("active");
            }
        });
        $(window).bind("scroll", function() {

            // 获取网页文档对象滚动条的垂直偏移
            var scrollTopNum = $(document).scrollTop(),
                    // 获取浏览器当前窗口的高度
                    winHeight = $(window).height(),
                    returnTop = $("div.returnTop");

            // 滚动条的垂直偏移大于 0 时显示，反之隐藏
            (scrollTopNum > 0) ? returnTop.fadeIn("fast") : returnTop.fadeOut("fast");

            // 给 IE6 定位
            if (!-[1, ] && !window.XMLHttpRequest) {
                returnTop.css("top", scrollTopNum + winHeight - 200);
            }

        });

        // 点击按钮后，滚动条的垂直方向的值逐渐变为0，也就是滑动向上的效果
        $("div.returnTop").click(function() {
            $("html, body").animate({scrollTop: 0}, 100);
        });
        $('.notify-text').marquee({
            auto: true,
            interval: 4000,
            showNum: 1,
            stepLen: 1,
            type: 'vertical'
        });
        if ($("#onlineBox").length > 0) {
            $(window).resize(function() {
                var height = $(window).height();
                var ul_height = $("#onlineBox").find("ul").height();
                if (ul_height > 220) {
                    if (height < 400) {
                        $("#onlineBox").find(".online_user_list").height(height - 200);
                    } else {
                        $("#onlineBox").find(".online_user_list").height(220);
                    }
                } else {
                    $("#onlineBox").find(".online_user_list").height(ul_height+10);
                }
            });
            $(window).trigger("resize");
            $("#onlineBox").find(".online_user_list").niceScroll({cursorcolor: "#ccc", "cursorwidth": "2px"});
        }
    });
</script>
<style>
    *{word-break:break-all; }
    #messageModal .control-label{width: 100px;}
    #messageModal .controls{margin-left:110px;}
    #messageModal input[readonly]{background: none;border:none;box-shadow:none;}
    .ac_results{border:1px solid #ccc !important;}
    .ac_results li{cursor: pointer;padding: 0px !important;}
    .ac_results li .options{height: 100%;cursor: pointer;padding: 8px;zoom: 1;clear: both;height:24px;line-height: 24px;}
    .ac_results li .options .message{max-width: 200px;background: #eff6fa;padding: 1px 10px 0;border-radius: 30px;text-decoration: none;margin: 0 5px 5px 0;display: inline-block;float: left;white-space: nowrap;text-overflow: ellipsis;overflow: hidden;}
    .ac_results li .options .tip{color: #999;margin: 0;margin-left: 4px;font-size: 12px;display: inline-block;}
    .ac_results li img{margin: 2px 10px 0 0;width: 25px;height: 25px;}
    .ac_results li:hover,.ac_over {background-color: #d6e9f8;color: #000;}
    .ac_results li .title{width:100%;background-color:#f6f6f6 !important;height:26px;line-height:26px;padding: 2px 8px;cursor: default;}
    .ac_odd{background-color: #fff;}
    #searchForm {display:inline-block;padding-top: 4px;}
    #searchForm .form-search{padding: 2px 4px;position: relative;border-radius: 4px;margin-right: 4px;}
    #searchForm .icon-search-2{z-index: 2;display:inline-block;height: 20px;width:20px;color:#ccc;font-size: 16px;}
    #searchForm .button{padding-right: 10px; padding-left: 10px;font-size: 14px;font-weight: 700;color: #fff;border:none;}
    #searchInput{padding-left:10px;padding-right: 16px;border-radius:4px !important;}
    .magnify-button{outline: none;position: absolute;right:3px;z-index: 4;width: 30px;height: 30px;border: 0;cursor: pointer;display: inline-block;background: 0;padding: 0;}
    #report-form .inline{width: 170px;margin-left:0;}
    .open   #moreOpt{background-color: #0081c2;color:#fff;border-radius: 0;}
    #moreOpt .caret{border-top-color: #fff;border-bottom-color: #fff;line-height: 45px;margin-top:20px;}
    .returnTop {position: fixed;_position: absolute;right: 10px;bottom: 200px;_bottom: auto;display: none;width: 40px;height: 40px;border-radius:4px;background: #fff;box-shadow: 0 0 5px #F5F5F5;text-indent: -9999px;cursor: pointer;background-color: #ddd;}
    .commentBox, .musicBox {position: fixed;_position: absolute;right: 10px;bottom: 154px;_bottom: auto;width: 40px;height: 40px;border-radius:4px;background: #fff;box-shadow: 0 0 5px #F5F5F5;cursor: pointer;}
    .commentBox a, .musicBox a{text-decoration: none;font-size: 20px;color:#fff;position: absolute;top: 0;_top:0;left: 0;display:inline-block;height: 40px;width: 40px;text-align: center;line-height: 40px;background-color: #ddd;border-radius:4px;}
    .returnTop:hover, .commentBox a:hover,.musicBox a:hover{background-color: #000;}
    .returnTop .arrow {position: absolute;top: -4px;_top: -20px;left: 8px;width: 0;height: 0;border-width: 12px;border-color: transparent transparent #fff;border-style: dashed dashed solid;}
    .returnTop .stick {position: absolute;top: 18px;left: 15px;height: 12px;width: 10px;background: #fff;}
    .musicBox{bottom: 108px;}
    /* 在线会员开始 */
    .online { position: fixed; width: 44px; left: 10px;; top: 100px; left: 2px; border:#bce8f1 solid 1px; background: #d9edf7; padding: 6px; border-radius: 4px; z-index: 999; }
    .online ul { padding: 0; margin: 4px 0; }
    .online ul li { list-style: none; margin-top: 5px;}
    .online ul img { width: 40px; height: 40px; border-radius: 4px; box-shadow: 0px 1px 1px rgba(0, 0, 0, 0.1); background: #fff; padding: 2px; }
    .online p { font-style:normal; font-size: 12px; color: #3a87ad; text-align: center; margin: 2px 0 0 0; line-height: 1.2em; }
    .online p strong{font-weight: bolder;}
    /* 在线会员结束 */

</style>
<script src="/js/jquery.marquee.min.js" type="text/javascript"></script>
<script src="/js/jquery.popbox.js" type="text/javascript"></script>
<?php
$modelSys = Sys::model()->find();
$this->setPageTitle($modelSys->browser_title != "" ? $modelSys->browser_title : Yii::app()->name);
$siteName = $modelSys->site_name != "" ? $modelSys->site_name : Yii::app()->name;
?>

<div class="returnTop" title="返回顶部">
    <span class="arrow"></span>
    <span class="stick"></span>
</div>
<div class="commentBox">
    <a href="<?php echo $this->createUrl("comment", array('type' => 'comment', 'action' => 'create')); ?>" title="反馈意见" class="icon-bubble-dots-4"></a>
</div>
<div class="header btn-primary T_bg" style="margin-bottom: 60px;">
    <div class="navbar">
        <ul class="pull-left nav nav-pills" style="margin-right:0;"><li style="width:100px;"><a href="<?php echo $this->createUrl('/' . Yii::app()->controller->id . '/index'); ?>"><img src="/images/logo.png"/></a></li></ul>
        <div id="searchForm" style="float:left;margin-left: 0px;">
            <form class="form-search" method="post" action="<?php echo $this->createUrl("/default/searchall"); ?>">
                <div class="input-append">
                    <input type="text" name="search" id="searchInput" placeholder="搜索话题、文章、问题或人..." style="width:300px;"/>
                    <button type="submit" class="magnify-button"><i class="icon-search-2" style="right:70px;"></i><span class="hide-text">搜索</span></button>
                </div>
            </form>
        </div>
        <?php
        $total = Message::model()->getUnreadMessage() + Notification::model()->getUnreadMessage();
        $this->widget('bootstrap.widgets.TbMenu', array(
            'type' => 'pills',
            'encodeLabel' => false,
            'items' => array(
                array('label' => '首页', 'url' => array('/' . Yii::app()->controller->id . '/index')),
                array('label' => '话题', 'url' => (Yii::app()->user->isGuest) ? array('/' . Yii::app()->controller->id . '/alltopic') : array('/' . Yii::app()->controller->id . '/mytopic')),
                array('label' => '问题', 'url' => array('/' . Yii::app()->controller->id . '/allquestion')),
                array('label' => '文章', 'url' => array('/' . Yii::app()->controller->id . '/allarticle')),
                (Yii::app()->user->isGuest) ? array() : array('label' => $total > 0 ? '消息' . '<span class="badge badge-important" style="position:absolute;top:10px;let:15px;">' . $total . '</span>' : '消息', 'url' => 'javascript:;', 'linkOptions' => array('id' => 'messagePopover', 'style' => $total > 0 ? 'width:50px;position:relative' : 'position:relative')),
                array('label' => '点评', 'url' => array('/' . Yii::app()->controller->id . '/comment')),
            )
        ));
        ?>
        <?php if (Yii::app()->user->isGuest) { ?>
            <?php
            $this->widget('bootstrap.widgets.TbMenu', array(
                'type' => 'pills',
                'htmlOptions' => array('class' => 'pull-right'),
                'items' => array(
                    array('label' => '注册', 'url' => '#', 'icon' => 'icon-user', 'linkOptions' => array("onclick" => "$('#loginModal').modal('show');$('#registerButton').trigger('click')")),
                    array('label' => '登陆', 'url' => '#', 'linkOptions' => array("onclick" => "$('#loginModal').modal('show');$('#loginButton').trigger('click')")),
                ),
            ));
            ?>
        <?php } else { ?>
            <ul class="pull-right nav nav-pills">
                <li class="dropdown" style="width:128px;">
                    <a  id="loginInfo" class="menu-link ellipsis dropdown-toggle" href="javascript:;"> <img style="margin-right:6px;" height="27" width="27" src="<?php echo $this->createUrl("/default/getimage", array("id" => Yii::app()->user->id, "type" => "avatar")); ?>"  alt="<?php echo User::getNameById(Yii::app()->user->id); ?>"><?php echo Yii::app()->user->name; ?></a>
                    <ul class="T_bg"  style="display: none" id="userMenu">
                        <li><a href="<?php echo $this->createUrl("/" . Yii::app()->controller->id . "/personal", array("type" => "personal")); ?>" target="_blank"><i class="icon-home"></i>我的主页</a></li>
                        <li><a href="<?php echo $this->createUrl("/" . Yii::app()->controller->id . "/task"); ?>"><i class="icon-clock"></i>任务中心</a></li>
                        <li><a href="<?php echo $this->createUrl("/" . Yii::app()->controller->id . "/inbox"); ?>"><i class="icon-envelop"></i>私信</a></li>
                        <li><a href="<?php echo $this->createUrl("/" . Yii::app()->controller->id . "/setting"); ?>"><i class="icon-cog-2"></i>设置</a></li>
                        <?php if (Yii::app()->user->name == "admin") { ?>
                            <li><a href="<?php echo $this->createUrl("/" . Yii::app()->controller->id . "/admin"); ?>"><i class="icon-cogs"></i>管理</a></li>
                        <?php } ?>
                        <li><a href="<?php echo $this->createUrl("/" . Yii::app()->controller->id . "/logout"); ?>"><i class="icon-switch"></i>退出</a></li>
                    </ul>
                </li>
            </ul>
        <?php } ?>
    </div>
</div>

<div class="alert alert-danger" id="notify-message">
    <div class="notify-text">
        <ul>
            <?php
            $status_text = Sys::model()->find()->status_text;
            if ($status_text != ""):
                $statusArray = array();
                $statusArray = explode("\n", $status_text);
                for ($i = 0; $i < count($statusArray); $i++) {
                    ?>
                    <li><?php echo $statusArray[$i]; ?></li>
                    <?php
                }
                ?>
            <?php endif; ?>
        </ul>
    </div>
</div>
<?php if (Yii::app()->user->hasFlash("success")): ?> 
    <div class="alert alert-success" id="successBox" style="border-radius: 0;width: 100%;text-align: center;z-index: 2;"><?php echo Yii::app()->user->getFlash("success"); ?></div>
<?php endif; ?>
<?php
//这是一段,在显示后定里消失的JQ代码,已集成至Yii中.
Yii::app()->clientScript->registerScript(
        'myHideEffect', '
var countdown = function(){
        var second = $("#time").text();
        second = parseInt(second);
        if(second == 1) {
          $("#successBox").animate({opacity: 1.0}).fadeOut("slow");
        } else {
            second = second - 1;
            $("#time").text(second);
            setTimeout(countdown,1000);
        }
    }

  setTimeout(countdown,1000);
  ', CClientScript::POS_READY
);
?>
<?php $userOnline = UserOnline::model()->findAll(array("order"=>"time desc")); ?>
<?php if (!empty($userOnline)) { ?>
    <div class="online" id="onlineBox">
        <p>在线<br><strong><?php echo count($userOnline); ?>人</strong></p>
        <div class="online_user_list">
            <ul>
                <?php foreach ($userOnline as $key => $value) { ?>
                    <li><a href="javascript::" class="user-label" data-id="<?php echo $value->id; ?>"><img src="<?php echo $this->createUrl("getimage", array("id" => $value->id, "type" => "avatar")); ?>"/></a><li>
                        <?php } ?>
            </ul>
        </div>
        <p>总人数<br><strong><?php echo User::model()->count(); ?>人</strong></p>
    </div>
    <?php
}?>