<style>
    .breadcrumbs{width: 1030px; margin: 0 auto;padding-top: 10px;}
    .content{margin: 0 auto; width: 1030px; }
</style>
<?php
 $this->pageTitle = "用户信息 - " .Yii::app()->name;
 ?>
<div class="breadcrumbs">
    <?php
    if ($_GET["type"] == "mood") {
        $links = array(
            "用户信息" => array("moodwall"),
            $_GET["action"] == "update" ? "我要修改心情" : "我要抒发心情"
        );
    } else {
        $links = array("用户信息");
    }
    $this->widget('zii.widgets.CBreadcrumbs', array(
        'links' => $links
    ));
    ?><!-- breadcrumbs -->
</div>
<div class="content clearfix">
    <div class="news">
        <div id="setting-tabs" class="tabs-above">
            <fieldset>
                <legend style="margin-bottom:10px;">用户信息</legend> 
            </fieldset>
            <div style="padding-top: 5px;">
                <div class="tabbable">
                    <ul class="nav nav-tabs">
                        <li class="<?php echo ($_GET['type'] == 'all' || !isset($_GET['type'])) ? "active" : '' ?>">
                            <a href="<?php echo $this->createUrl('alluser', array('type' => 'all')); ?>">所有用户</a>
                        </li>
                        <li class="<?php echo $_GET['type'] == 'new' ? "active" : '' ?>">
                            <a href="<?php echo $this->createUrl('alluser', array('type' => 'new')); ?>">最近注册用户</a>
                        </li>
                        <li class="<?php echo $_GET['type'] == 'create' ? "active" : '' ?>">
                            <a href="<?php echo $this->createUrl('alluser', array('type' => 'create')); ?>" style="<?php echo (Yii::app()->user->isGuest) ? '' : 'display:none'; ?>">我要注册</a>
                        </li>
                    </ul>
                    <div class="right-tab tab-content">
                        <div class="tab-pane active" id="tab1">
                            <?php if (($_GET['type'] == "all") || (!isset($_GET['type']))): ?>
                                <?php $this->renderPartial('../_usergrid', array('action' =>"all")); ?>  
                            <?php elseif (($_GET['type'] == "new")): ?>
                               <?php $this->renderPartial('../_usergrid', array('action' =>"new")); ?>  
                            <?php else: ?>
                                <?php $this->renderPartial('../_userform', array('userModel' =>new User()))?>
                            <?php endif; ?>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>