<style>
    .content{margin: 0 auto; width: 1030px; position: relative;}
    .content legend{border-bottom:none;}
    .content .control-label{text-align: left;}
</style>  
<?php $this->pageTitle = "管理 - " . Yii::app()->name;
$this->widget('bootstrap.widgets.TbBreadcrumbs', array('links' =>  array("管理")));
?>
<div class="content">
    <div class="tabbable">
        <ul class="nav nav-tabs">
            <li class="<?php echo ((!isset($_GET['type']) && $this->getAction()->getId()=="createsetting" || $this->getAction()->getId()=="updatedesktop") || (isset($_GET['type']) && ($_GET['type']=="user"))) ? "active" : '' ?>">
                <a href="<?php echo $this->createUrl('admin', array('type' => 'user')); ?>">用户管理</a>
            </li>
            <li class="<?php echo $_GET['type'] == 'syscomment' ? "active" : '' ?>">
                <a href="<?php echo $this->createUrl('admin', array('type' => 'syscomment')); ?>">点评管理</a>
            </li>
            <li class="<?php echo $_GET['type'] == 'sysreply' ? "active" : '' ?>">
                <a href="<?php echo $this->createUrl('admin', array('type' => 'sysreply')); ?>">点评回复管理</a>
            </li>
            <li class="<?php echo $_GET['type'] == 'topic' ? "active" : '' ?>">
                <a href="<?php echo $this->createUrl('admin', array('type' => 'topic')); ?>">话题管理</a>
            </li>
            <li class="<?php echo $_GET['type'] == 'question' ? "active" : '' ?>">
                <a href="<?php echo $this->createUrl('admin', array('type' => 'question')); ?>">问题管理</a>
            </li>
            <li class="<?php echo $_GET['type'] == 'article' ? "active" : '' ?>">
                <a href="<?php echo $this->createUrl('admin', array('type' => 'article')); ?>">文章管理</a>
            </li>    
            <li class="<?php echo $_GET['type'] == 'module' ? "active" : '' ?>">
                <a href="<?php echo $this->createUrl('admin', array('type' => 'module')); ?>">首页模块管理</a>
            </li> 
            <li class="<?php echo $_GET['type'] == 'link' ? "active" : '' ?>">
                <a href="<?php echo $this->createUrl('admin', array('type' => 'link')); ?>">友情链接管理</a>
            </li> 
            <li class="<?php echo $_GET['type'] == 'log' ? "active" : '' ?>">
                <a href="<?php echo $this->createUrl('admin', array('type' => 'log')); ?>">登陆日志管理</a>
            </li> 
             <li class="<?php echo ($this->getAction()->getId()=="createsetting" || $this->getAction()->getId()=="updatedesktop") ? "active" : '' ?>" style="<?php echo ($this->getAction()->getId()=="createsetting" || $this->getAction()->getId()=="updatedesktop") ? "" : 'display:none' ?>">
                <a href="<?php echo $this->createUrl($this->getAction()->getId()); ?>"> <? echo $this->getAction()->getId()=="createsetting" ? "新建":"修改";?>首页桌面模块</a>
            </li>
            <li class="<?php echo ($this->getAction()->getId()=="createlink" || $this->getAction()->getId()=="updatelink") ? "active" : '' ?>" style="<?php echo ($this->getAction()->getId()=="createlink" || $this->getAction()->getId()=="updatelink") ? "" : 'display:none' ?>">
                <a href="<?php echo $this->createUrl($this->getAction()->getId()); ?>"> <? echo $this->getAction()->getId()=="createlink" ? "新建":"修改";?>友情链接</a>
            </li>
            <li class="<?php echo ($this->getAction()->getId()=="updateuser") ? "active" : '' ?>" style="<?php echo ($this->getAction()->getId()=="updateuser") ? "" : 'display:none' ?>">
                <a href="<?php echo $this->createUrl($this->getAction()->getId()); ?>">修改用户</a>
            </li>
            
        </ul>
        <div class="right-tab tab-content">
            <div class="tab-pane active">
                <?php
                if(($this->getAction()->getId()=="updateuser")){
                        $this->renderPartial('../_userform',array('userModel'=>$userModel,'successMessage'=>$successMessage));    
                }else if(($this->getAction()->getId()=="createsetting") || ($this->getAction()->getId()=="updatedesktop")){
                        $this->renderPartial('../_desktopform',array('model'=>$desktopSettingModel,'successMessage'=>$successMessage));    
                }else if(($this->getAction()->getId()=="createlink") || ($this->getAction()->getId()=="updatelink")){
                        $this->renderPartial('../_linkform',array('model'=>$modelLink,'successMessage'=>$successMessage));    
                }elseif (($_GET["type"] == "user" || !isset($_GET['type'])) && (Yii::app()->user->name == 'admin')) {
                    ?>
                    <?php $this->renderPartial('../_userinfo', array("action" => "all")); ?>  
                    <?php
                } elseif (($_GET["type"] == "syscomment") && (Yii::app()->user->name == 'admin')) {
                    ?>
                    <?php $this->renderPartial('../_syscommentgrid'); ?>  
                    <?php
                } elseif (($_GET["type"] == "topic") && (Yii::app()->user->name == 'admin')) {
                    ?>
                    <?php $this->renderPartial('../_topicgrid',array("action" => "all")); ?>
                    <?php
                } elseif (($_GET["type"] == "sysreply") && (Yii::app()->user->name == 'admin')) {
                    ?>
                    <?php $this->renderPartial('../_sysreplygrid'); ?>  
                    <?php
                }elseif (($_GET["type"] == "question") && (Yii::app()->user->name == 'admin')) {
                    ?>
                    <?php $this->renderPartial('../_questiongrid'); ?>  
                    <?php
                }elseif (($_GET["type"] == "article") && (Yii::app()->user->name == 'admin')) {
                    ?>
                    <?php $this->renderPartial('../_articlegrid'); ?>  
                    <?php
                }elseif (($_GET["type"] == "module") && (Yii::app()->user->name == 'admin')) {
                    ?>
                    <?php $this->renderPartial('../_desktopgrid'); ?>  
                    <?php
                }elseif (($_GET["type"] == "link") && (Yii::app()->user->name == 'admin')) {
                    ?>
                    <?php $this->renderPartial('../_linkgrid'); ?>  
                    <?php
                }elseif (($_GET["type"] == "log") && (Yii::app()->user->name == 'admin')) {
                    ?>
                    <?php $this->renderPartial('../_loggrid'); ?>  
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
</div>   
<?php $this->beginWidget('bootstrap.widgets.TbModal', array('id' => 'resetpassword')); ?>
<div class="modal-header">
    <a class="close" data-dismiss="modal">×</a>
    <h4>重置用户密码</h4>
</div>
<div class="modal-body">
    <div class="form">

    <?php
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => 'init-user-form', 
        'type' => 'horizontal',
        'enableAjaxValidation' => false,
        'enableClientValidation' => true,
        'action'=>$this->createUrl("initpassword")
            ));
    $model = new User;
    ?>
    <?php  echo $form->hiddenField($model, 'id',array('id'=>'initUserId'));?>
    <?php  echo $form->textFieldRow($model, 'user_name',array('id'=>'initUsername','readOnly'=>true));?>
    <?php echo $form->textFieldRow($model, 'password',array('id'=>'initPassword')); ?>
    <?php $this->endWidget(); ?>
</div><!-- form -->
</div>
<div class="modal-footer" style="text-align:center">
    <?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'button',
        'type' => 'danger',
        'label' => '保存',
        'htmlOptions' => array('id' => 'initSumbit'),
    ));
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'button',
        'label' => '关闭',
        'htmlOptions' => array("data-dismiss" => "modal"),
    ));
    ?>
</div>
<?php $this->endWidget(); ?>