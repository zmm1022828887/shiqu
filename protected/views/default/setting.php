<style>
    .content{margin: 0 auto; width: 1030px; position: relative;padding-top: 30px;}
    .content legend{border-bottom:none;}
    .content .control-label{text-align: left;}
 </style>  
 <?php  $this->pageTitle = "设置 - " .Yii::app()->name;?>
<div class="content">
    <div class="tabbable">
        <ul class="nav nav-tabs">
            <li class="<?php echo ($_GET['type'] == 'info' || !isset($_GET['type'])) ? "active" : '' ?>">
                <a href="<?php echo $this->createUrl('setting', array('type' => 'info')); ?>">用户账号</a>
            </li>
            <li class="<?php echo $_GET['type'] == 'password' ? "active" : '' ?>">
                <a href="<?php echo $this->createUrl('setting', array('type' => 'password')); ?>">用户密码</a>
            </li>
            <li class="<?php echo $_GET['type'] == 'tags' ? "active" : '' ?>">
                <a href="<?php echo $this->createUrl('setting', array('type' => 'tags')); ?>">个人标签</a>
            </li>
            <li class="<?php echo $_GET['type'] == 'message' ? "active" : '' ?>">
                <a href="<?php echo $this->createUrl('setting', array('type' => 'message')); ?>">消息设置</a>
            </li>
            <li class="<?php echo $_GET['type'] == 'filter' ? "active" : '' ?>">
                <a href="<?php echo $this->createUrl('setting', array('type' => 'filter')); ?>">屏蔽</a>
            </li>
            <?php if(Yii::app()->user->name == "admin"){?>
             <li class="<?php echo $_GET['type'] == 'wealth' ? "active" : '' ?>">
                <a href="<?php echo $this->createUrl('setting', array('type' => 'wealth')); ?>">财富值设置</a>
            </li>
             <li class="<?php echo $_GET['type'] == 'sys' ? "active" : '' ?>">
                <a href="<?php echo $this->createUrl('updatesys', array('type' => 'sys')); ?>">网站信息设置</a>
            </li>
            <?php }?>
        </ul>
        <div class="right-tab tab-content">
            <div class="tab-pane active">
              <?php 
              if ($_GET['type'] == 'info' || !isset($_GET['type']))
                    $this->renderPartial('../_personInfo', array('userModel' => $userModel,"successMessage"=>$successMessage));
              else if(Yii::app()->user->name == "admin" && ($_GET['type']=="wealth"))
                  $this->renderPartial('../_wealthsetting',array('model'=>new WealthForm,"successMessage"=>$successMessage));
              else if(Yii::app()->user->name == "admin" &&  ($_GET['type']=="sys"))
                  $this->renderPartial('../_sysform', array("model" => $sysModel,'successMessage'=>$successMessage));
              else if(($_GET['type']=="tags"))
                  $this->renderPartial('../_tagsform', array("model" => $sysModel,"userModel" => $userModel,'successMessage'=>$successMessage));
               else if(($_GET['type']=="filter"))
                  $this->renderPartial('../_followeeslist', array("type" => "followers", 'followers' => $userModel->block_users));
                //  $this->renderPartial('../_sysform',array('model'=>new WealthForm,"successMessage"=>$successMessage));
              else if($_GET['type']=="password")
                  $this->renderPartial('../_password',array("errorMessage"=>$errorMessage,"successMessage"=>$successMessage,"userModel"=>$userModel));
              else if ($_GET['type'] == 'message')
                    $this->renderPartial('../_messagesetting', array('userModel' => $userModel,"successMessage"=>$successMessage));
               ?>
            </div>

        </div>
    </div>
</div>   