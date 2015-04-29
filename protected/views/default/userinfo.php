<style>
    .main{width: 1030px;margin:0 auto;padding: 25px 0 50px;}
    .main .main-left{width:800px; float: left;padding-bottom: 10px; }
    .main  .main-left .profile-info {border: 1px solid #ddd;border-radius: 4px;box-shadow: 0 1px 0 #f2f4f5;} 
    .main  .main-left .profile-info  .profile-header-main{padding: 12px 18px;}
    .main  .main-left .profile-info .top{font-size: 1.4em;margin-bottom: 10px;}
    .main  .main-left .profile-info .name{ font-weight: 700;color: #222;}
    .profile-header-info{padding-left: 4px;width:638px;}
    .profile-header-info td,.profile-header-info th{padding: 4px 6px;}
    .profile-header-operation {padding: 12px 18px;border-top: 1px dotted #eee;height: 24px;}
    .profile-header-op-btns{float: right;}
    .profile-section-wrap{margin-top: 25px;border: 1px solid #ddd;overflow: hidden;border-radius: 4px;box-shadow: 0 1px 0 #f2f4f5;}
    .profile-section-wrap legend{padding: 7px 14px;font-size: 14px;height: 26px;line-height: 26px;overflow: hidden;}
    .profile-section-wrap .grid-view,.profile-section-wrap .list-view,.profile-section-wrap .alert-info {padding: 7px 14px;margin: 0 auto 10px;}
    .main  .main-left .profile-navbar {background-color: #fcfcfc;border-top: 1px solid #ddd;box-shadow: 0 2px 2px #f0f0f0 inset;border-radius: 0 0 4px 4px;}
    .main  .main-left .profile-navbar .item {float: left;padding: 12px 20px;font-weight: 700;color: #666;text-align: center;font-size: 14px;text-decoration: none;line-height: 22px;}
    .main  .main-left  .profile-navbar .item.home.active {border-color: #ddd;color:#ccc;}
    .main  .main-left  .profile-navbar .item.home{border-right: 1px solid #eee;}
    .main  .main-left  .profile-navbar .item.active {color: #222;border: solid #ddd;border-width: 0 1px;background-color: #f7f7f7;box-shadow: 0 2px 2px #f0f0f0 inset;}
    .main .main-left .item .num{color:#ff0000;}
    .main  ul{padding-left: 0px;padding-right: 0px;}
    .main  .item-category a{color:#666666;margin: 0;padding-left:20px;padding-right: 0px;}
    .main .main-right{width:200px; float: right;}
    .main .main-right .profile-side-following{border-bottom: 1px solid #eee;margin-bottom: 15px;}
    .main .main-right .item{float: left;text-decoration: none;padding: 2px 30px 8px 0;}
    .main .main-right .item .gray-normal,.main .main-right .link-gray-normal{ color: #999;font-weight: 400;}
    .main .main-right .item strong{ font-size: 16px;font-weight: 700;color: #666;}
    .main .main-right .item label{font-size: 13px;font-weight: 400;vertical-align: 1px;color: #666;cursor: pointer;display: inline-block;}

    .main .main-right .profile-side-section-title {font-size: 14px;font-weight: 700;color: #666;padding-bottom: 10px;padding-top: 0;line-height: 1;white-space: nowrap;display: inline-block}
    .main .main-right .link{float: left;margin-right: 4px;}
    .main .main-right .link-litblue{font-size: 14px;}
    .main .main-right .avatar {display: block;height: 34px;width: 34px;border-radius: 3px;}
    .main .main-right .profile-side-section{padding: 2px 0;}
    .main .main-right .profile-side-section+.profile-side-section{padding-top: 15px;margin-top: 15px;border-top: 1px solid #eee;}
    .user-list{margin-left:4px;display: inline-block;text-align: center;}
    .user-list .user-name{position:absolute;bottom:0px;right:0px;display:inline-block;width: 70px;word-wrap: break-word;overflow: hidden;height: 20px;line-height: 20px;}
    .user-list .remove-visit{position:absolute;top:0px;right:0px;vertical-align: top;height: 12px;width: 12px;line-height: 12px;font-size: 12px;text-align: center;text-decoration: none;border-radius: 2px;display: block;color: #FFFFFF;background-color: #49afcd;visibility: hidden;}
    .user-list:hover .remove-visit{ visibility: visible !important;}
</style>

<?php $userModel = User::model()->findByPk($_GET["user_id"]); ?>
<?php
$this->pageTitle = $userModel->user_name . "个人信息 - " . Yii::app()->name;
$cs = Yii::app()->clientScript;
$cs->registerCoreScript('cookie');
$countArray = unserialize($userModel->visit_count);
$userModel->visit_count = $countArray['visit_count'] ? $countArray['visit_count'] : 0;
$privArray = unserialize($userModel->priv);
?>
<?php if (trim($bgMusic) != "") { ?>
    <div class="musicBox">
        <a title="点此关闭背景音乐" class="music_start" href="javascript:;"></a>
    </div>
<?php } ?>
<div class="main clearfix">
    <div class="main-left">
        <div class="profile-info private_list" data-uid="<?php echo $userModel->id; ?>" data-username="<?php echo $userModel->user_name; ?>">
            <div class="profile-header-main">
                <div class="avatar_info clearfix">
                    <div class="top">

                        <div class="title-section ellipsis">
                            <a class="name" href="javascript:;"><?php echo User::getNameById($_GET["user_id"]); ?></a>
                            <?php if ($userModel->signature != "") { ?>，<span class="bio" title="<?php echo $userModel->signature; ?>"><?php echo $userModel->signature; ?></span>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="body">
                        <div id="avatar" style="float: left;">
                            <img height="100" width="100"  alt="<?php echo $userModel->user_name; ?>" src="<?php echo $this->createUrl("getimage", array("id" => $_GET["user_id"], "type" => "avatar")); ?>">
                        </div>
                        <div class="profile-header-info pull-left">
                            <table style="width:100%;"><tbody><tr class="odd"><th style='width: 10px;'><i class="icon-male"></i></th><td><?php echo $userModel->gender == 1 ? "男" : "女";?><a href="<?php echo ($_GET["type"] == "about") ? $this->createUrl("default/userinfo", array("user_id" => $_GET["user_id"])) : $this->createUrl("default/userinfo", array("type" => "about", "user_id" => $_GET["user_id"])); ?>" class="pull-right"><i class='<?php echo ($_GET["type"] == "about") ? "icon-arrow-left-4" : "icon-arrow-right-5"; ?>'></i> <?php echo ($_GET["type"] == "about") ? "返回个人主页" : "查看详细资料"; ?></a></td></tr>
                                    <tr class="even"><th><i class="icon-tag-5"></i></th><td>
                                            <?php
                                            if ($userModel->tags != "") {
                                                $tagsArray = explode(",", $userModel->tags);
                                                for ($i = 0; $i < count($tagsArray); $i++) {
                                                    ?>
                                                    <a title="<?php echo $tagsArray[$i]; ?>"  href="<?php echo $this->createUrl("/default/query", array("q" => $tagsArray[$i], "type" => "user")); ?>" class="label <?php echo (isset($_GET["q"]) && ($_GET["q"] == $tagsArray[$i])) ? 'label-info' : ''; ?>"><?php echo $tagsArray[$i]; ?></a> 
                                                    <?php
                                                }
                                            } else {
                                                echo "<span style='color:#999'>暂无个人标签</span>";
                                            }
                                            ?>
                                        </td></tr>
                                    <tr class="odd"><th><i class="icon-pencil-2"></i></th><td>
                                            <?php
                                            if ($userModel->desc != "") {
                                                echo $userModel->desc;
                                            } else {
                                                echo "<span style='color:#999'>暂无个人简介</span>";
                                            }
                                            ?></td></tr>
                                </tbody></table>
                        </div> 
                    </div>
                </div>
            </div>
            <div class="profile-header-operation">
                <div class="profile-header-info-list pull-left">
                    <span class="profile-header-info-title" style="margin-right: 4px;">获得</span>
                    <i class="icon-thumbs-up"></i>  <span style="color:#ff0000"><?php echo Vote::model()->count("to_user=:to_user and opinion=0",array(":to_user"=> $userModel->id));?> </span> 赞同
                    <a style="color:#666;margin-left: 10px;" class="item <?php echo ($_GET["type"] == "wealth") ? "active" : ""; ?>" href="<?php echo $this->createUrl("default/userinfo", array("type" => "wealth", "user_id" => $_GET["user_id"])); ?>">

                        <i class="icon-coins"></i> <span class="num"><?php echo $userModel->wealth; ?></span> 财富值
                    </a>
                </div>
                <div class="profile-header-op-btns clearfix">
                    <button  class="btn btn-success btn-small" style="width:78px;" data-uid="<?php echo $_GET["user_id"]; ?>"  name="<?php echo Yii::app()->user->isGuest ? 'noLogin' : 'attention'; ?>"><?php echo (!in_array($_GET["user_id"], explode(",", User::model()->findByPk(Yii::app()->user->id)->followees)) || Yii::app()->user->isGuest) ? "立即关注" : "取消关注"; ?></button>
                    <a  class="btn btn-small" data-uid="<?php echo $_GET["user_id"]; ?>" data-username="<?php echo User::getNameById($_GET["user_id"]); ?>" name="<?php echo Yii::app()->user->isGuest ? 'noLogin' : 'reply'; ?>"><i class="icon-envelop"></i></a>
                </div>
            </div>
            <div class="profile-navbar clearfix">
                <a class="item home first <?php echo (!isset($_GET["type"])) ? "active" : ""; ?>" href="<?php echo $this->createUrl("default/userinfo", array("user_id" => $_GET["user_id"])); ?>">
                    <i class="icon icon-home"></i><span class="hide-text">主页</span>
                </a>
                <a class="item <?php echo ($_GET["type"] == "question") ? "active" : ""; ?>" href="<?php echo $this->createUrl("default/userinfo", array("type" => "question", "user_id" => $_GET["user_id"])); ?>">
                    问题
                    <span class="num"><?php echo Question::model()->count("create_user=" . $userModel->id); ?></span>
                </a>
                <a class="item <?php echo ($_GET["type"] == "answer") ? "active" : ""; ?>" href="<?php echo $this->createUrl("default/userinfo", array("type" => "answer", "user_id" => $_GET["user_id"])); ?>">
                    回答
                    <span class="num"><?php echo Answer::model()->count("is_anonymous=0 and create_user=" . $userModel->id); ?></span>
                </a>
                <a class="item <?php echo ($_GET["type"] == "article") ? "active" : ""; ?>" href="<?php echo $this->createUrl("default/userinfo", array("type" => "article", "user_id" => $_GET["user_id"])); ?>">
                    文章
                    <span class="num"><?php echo Article::model()->count("publish=1 and create_user=" . $userModel->id); ?></span>
                </a>
            </div>
        </div>
       <?php if ((!isset($_GET["type"])) && ($userModel->topic_status == 1)) { ?>
            <div class="profile-section-wrap skilled-topics clearfix"> 
                <fieldset style="overflow: hidden;">
                    <legend><b><?php echo $userModel->user_name; ?></b>擅长的话题</legend>
                </fieldset>
                <div class="profile-section-list clearfix">
                    <?php $skillTopicArray = explode(",", trim($userModel->topic_ids, ",")); ?>
                    <?php
                    for ($k = 0; $k < count($skillTopicArray); $k++) {
                        $skillTopicModel = Topic::model()->findByPk($skillTopicArray[$k]);
                        ?>
                        <div class="item">
                            <span class="avatar"><img src="<?php echo $this->createUrl("getimage", array("type" => "topic", "id" => $skillTopicModel->id)); ?>"></span>
                            <div class="content">
                                <div class="content-inner">
                                    <h3 class="zg-gray-darker"><?php echo $skillTopicModel->name; ?></h3>
                                    <p class="meta">
                                        <span><a href="javascript:;" title="<?php echo $userModel->user_name;?>在话题<?php echo $skillTopicModel->name; ?>获得赞"><i class="icon-thumbs-up"></i><?php echo User::getTotalByTypeId($skillTopicModel->id, "disagree", $userModel->id);?></a></span>
                                        <span><a href="<?php echo $this->createUrl("userinfo", array("type" => "skilltopic", "id" => $skillTopicModel->id, "name" => "answer","user_id"=>$userModel->id)); ?>" title="<?php echo $userModel->user_name;?>在<?php echo $skillTopicModel->name; ?>下的回答"><i class="icon-bubble"></i><?php echo User::getTotalByTypeId($skillTopicModel->id, "answer", $userModel->id);?></a></span> 
                                        <span><a href="<?php echo $this->createUrl("userinfo", array("type" => "skilltopic", "id" => $skillTopicModel->id, "name" => "article","user_id"=>$userModel->id)); ?>" title="<?php echo $userModel->user_name;?>在<?php echo $skillTopicModel->name; ?>下发表的文章"><i class="icon-file-4"></i><?php echo User::getTotalByTypeId($skillTopicModel->id, "article", $userModel->id);?></a></span>
                                        <span><a href="<?php echo $this->createUrl("userinfo", array("type" => "skilltopic", "id" => $skillTopicModel->id, "name" => "question","user_id"=>$userModel->id)); ?>" title="<?php echo $userModel->user_name;?>在<?php echo $skillTopicModel->name; ?>下的提问"><i class="icon-question"></i><?php echo User::getTotalByTypeId($skillTopicModel->id, "question", $userModel->id);?></a></span> 
                                    </p>
                                </div>
                            </div>
                        </div>
                        <?php if (($k + 1) % 2 == 0) { ?>
                            <div class="border"></div>
                        <?php } ?>
                    <?php } ?>

                </div>
            </div>
        <?php } ?>
        <div class="profile-section-wrap">
            <?php if (!isset($_GET["type"])) { ?>  
            
            
                <fieldset>
                    <legend style="margin-bottom:10px;"><b><?php echo $userModel->user_name; ?></b>最新动态</legend> 
                </fieldset>

                <?php
                $criteria = new CDbCriteria;
                if(Yii::app()->user->name!="admin"){
                   $criteria->addNotInCondition("notification_type",array("report","reportanswer", "reportquestion", "reportarticle"));
                }
                $criteria->addCondition("from_id ='" . $_GET["user_id"] . "'");
                $newDataProvider = new CActiveDataProvider('NotificationContent', array(
                    'criteria' => $criteria,
                    'sort' => array(
                        'defaultOrder' => 'send_time  desc'
                    )
                ));
                $this->widget('bootstrap.widgets.TbListView', array(
                    'dataProvider' => $newDataProvider,
                    'template' => '{items}{pager}',
                    'itemView' => '../_homeview',
                    'htmlOptions' => array('style' => 'padding-top:0px')
                ));
                ?>       
            <?php } elseif ($_GET["type"] == "followees") { ?>  
                <fieldset>
                    <legend style="margin-bottom:10px;"><b><?php echo $userModel->user_name; ?></b> 关注了<?php echo $userModel->followees == '' ? 0 : count(explode(",", trim($userModel->followees, ","))); ?>个人</legend> 
                </fieldset>
                <?php $this->renderPartial('../_followeeslist', array('type' => 'followees', 'followees' => $userModel->followees)); ?>
            <?php } elseif ($_GET["type"] == "followers") { ?>  
                <fieldset>
                    <legend style="margin-bottom:10px;"><b><?php echo $userModel->user_name; ?></b> 被<?php echo $userModel->followers == '' ? 0 : count(explode(",", trim($userModel->followers, ","))); ?>人关注</legend> 
                </fieldset>
                <?php $this->renderPartial('../_followeeslist', array('type' => 'followers', 'followers' => $userModel->followers)); ?>
            <?php } elseif ($_GET["type"] == "question") { ?>  
                <fieldset>
                    <legend style="margin-bottom:10px;"><b><?php echo $userModel->user_name; ?></b> 提出了 <?php echo Question::model()->count("create_user=" . $userModel->id); ?>个问题</legend> 
                </fieldset>
                <div class="vote-tabs">
                    <?php $this->renderPartial('../_questiontabs', array('type' => 'new')); ?>
                </div>
            <?php } elseif ($_GET["type"] == "article") { ?>  
                <fieldset>
                    <legend style="margin-bottom:10px;"><b><?php echo $userModel->user_name; ?></b> 发表了<?php echo Article::model()->count("publish=1 and create_user=" . $userModel->id); ?>篇文章</legend> 
                </fieldset>
                <div class="vote-tabs">
                    <?php $this->renderPartial('../_articletabs', array('type' => 'new')); ?>
                </div>
            <?php } elseif ($_GET["type"] == "topic") { ?>  
                <fieldset>
                    <legend style="margin-bottom:10px;"><b><?php echo $userModel->user_name; ?></b> 关注了<?php echo Topic::model()->count("join_user like '%," . $userModel->id . ",%'");?>个话题</legend> 
                </fieldset>
                <?php $this->renderPartial('../_jointopiclist'); ?>
            <?php }elseif ($_GET["type"] == "skilltopic") { 
                
               $skillTopicModels = Topic::model()->findByPk($_GET["id"]);
                ?>  
                <fieldset>
                    <legend style="margin-bottom:10px;">
                        <b><?php echo $userModel->user_name;?></b> 在 <b><a href="<?php echo $this->createUrl("topic",array("id"=>$skillTopicModels->id));?>"><?php echo $skillTopicModels->name;?></a></b> 话题下的
                        <?php echo $_GET["name"]=="answer" ? "回答": ($_GET["name"]=="question" ? "提问":"发表的文章");?>
                        <?php echo "（".User::getTotalByTypeId($skillTopicModels->id, $_GET["name"],$userModel->id)."）"?>
                    </legend> 
                </fieldset>
                <?php if($_GET["name"]=="answer"){
                $criteria = new CDbCriteria;
                $criteria->addCondition("create_user=" . $userModel->id);
                $criteria->addCondition("is_anonymous=0");
                $criteria->addInCondition("question_id",Topic::getQuestionArray($skillTopicModels->id));
                $answerDataProvider = new CActiveDataProvider('Answer', array(
                    'criteria' => $criteria,
                    'sort' => array(
                        'defaultOrder' => 'create_time  desc'
                    )
                ));
                $this->widget('bootstrap.widgets.TbListView', array(
                    'dataProvider' => $answerDataProvider,
                    'template' => '{items}{pager}',
                    'itemView' => '../_answerview',
                    'id' => 'answerListView',
                    'htmlOptions' => array('style' => 'padding-top:0px')
                ));?>
                <?php }else if($_GET["name"]=="question"){?>
                <?php $this->renderPartial('../_questiontabs',array('type' => 'skilltopic')); ?>
                <?php }else{?>
             <?php $this->renderPartial('../_articletabs',array('type' => 'skilltopic')); ?>
              <?php }?>
            <?}elseif ($_GET["type"] == "wealth") {
                ?>  
                <fieldset>
                    <legend style="margin-bottom:10px;"><b><?php echo $userModel->user_name; ?></b> 财富值的详细记录</legend> 
                </fieldset>
                <?php
                $criteria = new CDbCriteria;
                $criteria->addCondition("create_user =" . $userModel->id);
                $wealthDataProvider = new CActiveDataProvider('Wealth', array(
                    'criteria' => $criteria,
                    'sort' => array(
                        'defaultOrder' => 'create_time  desc'
                    )
                ));
                $columns = array(
                    array('name' => 'content'),
                    array('name' => 'create_time', 'value' => 'date("Y-m-d H:i:s",$data->create_time)', 'headerHtmlOptions' => array('style' => 'width:140px;')),
                );
                $this->widget('bootstrap.widgets.TbGridView', array(
                    'template' => '{items}{pager}',
                    'dataProvider' => $wealthDataProvider,
                    'columns' => $columns,
                    'id' => 'wealth-list'
                ));
                ?>
            <?php
            } elseif ($_GET["type"] == "answer") {
                $criteria = new CDbCriteria;
                
                $criteria->addCondition("create_user=" . $userModel->id);
                $criteria->addCondition("is_anonymous=0");
                ?>  
                <fieldset style="overflow: hidden;">
                    <legend style="margin-bottom:10px;"><b><?php echo $userModel->user_name; ?></b> 回答了<?php echo Answer::model()->count($criteria); ?>个问题</legend> 
                </fieldset>
                <?php
                $newDataProvider = new CActiveDataProvider('Answer', array(
                    'criteria' => $criteria,
                    'sort' => array(
                        'defaultOrder' => 'create_time  desc'
                    )
                ));
                $this->widget('bootstrap.widgets.TbListView', array(
                    'dataProvider' => $newDataProvider,
                    'template' => '{items}{pager}',
                    'itemView' => '../_answerview',
                    'htmlOptions' => array('style' => 'padding-top:0px')
                ));
                ?> 
            <?php } elseif ($_GET["type"] == "about") { ?>
                <fieldset>
                    <legend style="margin-bottom:10px;"><b><?php echo $userModel->user_name; ?></b> 的详细信息</legend>      
                </fieldset>
                <?php
                $this->widget('bootstrap.widgets.TbDetailView', array(
                    'data' => $userModel,
                    'attributes' => array(
                        'user_name',
                        array(
                            'name' => 'register_time',
                            'value' => date("Y-m-d H:i", $userModel->register_time)
                        ),
                        array(
                            'name' => 'gender',
                            'value' => $userModel->gender == 1 ? "男" : "女"
                        ),
                        'signature',
                        'desc',
                        'tags',
                        array(
                            'name' => 'last_visit_time',
                            'value' => $userModel->last_visit_time == 0 ? "" : date("Y-m-d H:i", $userModel->last_visit_time)
                        ),
                    ),
                ));
                ?>
            <?php }; ?>
        </div>
    </div>
    <div class="main-right"> 
        <div class="profile-side-following clearfix">
            <a class="item" href="<?php echo $this->createUrl("/default/userinfo", array("type" => "followees", "user_id" => $_GET["user_id"])); ?>">
                <span class="gray-normal">关注了</span><br>
                <strong><?php echo $userModel->followees == '' ? 0 : count(explode(",", trim($userModel->followees, ","))); ?></strong><label> 人</label>
            </a>
            <a class="item" href="<?php echo $this->createUrl("/default/userinfo", array("type" => "followers", "user_id" => $_GET["user_id"])); ?>"  style="border-left: 1px solid #eee;padding-left:20px;">
                <span class="gray-normal">关注者</span><br>
                <strong><?php echo $userModel->followers == '' ? 0 : count(explode(",", trim($userModel->followers, ","))); ?></strong><label> 人</label>
            </a>
        </div>
        <div class="profile-side-section">
            <div class="side-section-inner clearfix">
                <?php $userCount = Topic::model()->count("join_user like '%," . $userModel->id . ",%'"); ?>
                <div class="profile-side-section-title">
                    关注了 <a href="<?php echo $this->createUrl("/default/userinfo", array('type' => 'topic', 'user_id' => $_GET["user_id"])); ?>" class="link-litblue"><strong><?php echo $userCount; ?> 个话题</strong></a>
                </div>
                <?php if ($userCount > 0) { ?>
                    <div class="profile-side-columns">
                        <?php
                        $criteria = new CDbCriteria;
                        $criteria->addSearchCondition('join_user', "," . $userModel->id . ",");
                        $topicModel = Topic::model()->findAll($criteria);
                        $i = 0;
                        foreach ($topicModel as $topic) {
                            $i++;
                            if ($i == 7)
                                break
                                ?>
                            <div>
                                <a href="<?php echo $this->createUrl("/default/topic", array("id" => $topic->id)); ?>" class="link topic-label" data-id="<?php echo $topic->id; ?>">  <img alt="<?php echo $topic->name; ?>" class="avatar" src="<?php echo $this->createUrl("getimage", array("id" => $topic->id, "type" => "topic")); ?>"></a>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
        <div class="profile-side-section">
            <div class="side-section-inner">
                <ul id="profile-side-op" class="profile-side-op">
                    <li><a href="javascript:void(0);" data-uid="<?php echo $userModel->id; ?>" name="<?php echo Yii::app()->user->isGuest ? 'noLogin' : 'report'; ?>" class="link-gray-normal">举报用户</a></li>
                    <li><a href="javascript:void(0);" data-uid="<?php echo $userModel->id; ?>" block-value="<?php echo ((Yii::app()->user->isGuest) || (!in_array($userModel->id, explode(",", User::model()->findByPk(Yii::app()->user->id)->block_users)))) ? "0" : "1"; ?>" name="<?php echo Yii::app()->user->isGuest ? 'noLogin' : 'block'; ?>"  class="link-gray-normal"><?php echo ((Yii::app()->user->isGuest) || (!in_array($userModel->id, explode(",", User::model()->findByPk(Yii::app()->user->id)->block_users)))) ? "屏蔽用户" : "取消屏蔽"; ?></a></li>
                </ul>
            </div>
        </div>
        <div class="profile-side-section">
            <div class="side-section-inner clearfix">
                <div class="profile-side-section-title">最近访客</div>
                <?php
                $this->renderPartial('../_visituser', array("type" => "visited", "user_id" => $userModel->id));
                ?>
            </div>
        </div>
        <div class="profile-side-section" style="border-bottom: none;border-top:1px solid #ccc;">
            <div class="side-section-inner">
                <span class="gray-normal">个人主页被 <strong><?php echo $userModel->visit_count; ?></strong> 人浏览</span>
            </div>
        </div>
    </div>
</div>
<script>
    var toUid = "<?php echo trim($_GET["user_id"]); ?>";
    var deleteVisitUrl = "<?php echo Yii::app()->controller->createUrl("deletevisit"); ?>"; //删除描述ction
    var isLogin = "<?php echo Yii::app()->user->isGuest ? "false":"true"; ?>";
    var agreeVoteUrl = "<?php echo $this->createUrl("agreeavote"); ?>";
    var disagreeVoteUrl = "<?php echo $this->createUrl("disagreevote"); ?>";
        var changeCommentUrl = "<?php echo $this->createUrl("changecomment"); ?>";
   function report(pk,type){
            if(isLogin=="false"){
                 $("[name='noLogin']").trigger("click");
                  return false;
            }
            $('#reportModal').modal('show');
            $('#reportModal').find(".modal-title").text(type=="question" ? "为什么举报该问题？":"为什么举报该回答？");
            $("#Message_report_content").hide();
            $("#Message_report_uid").val(pk);
            $("#Message_report_model").val(type);
            return false;
   }
      function changeComment(answerId,type){
                    $.post(changeCommentUrl, {'answerId': answerId,'type':type}, function(data) {
                        if(data=="ok"){
                              $.fn.yiiListView.update("answerListView");
                        }else{
                             alert("删除失败");
                        }
                    });
    }
</script>
<script>
    $(document).ready(function() {
        $(".main-left li.main-category .main-link").toggle(function() {
            $(this).addClass("active");
            $(this).next().hide(500);
        }, function() {
            $(this).removeClass("active");
            $(this).next().show(500);
        });
        $(document).delegate("#userinfo-comment-list a[data-name='delete-comment']", 'click', function() {
            if (window.confirm("删除评论时，此评论下的回复也会全部删除，确定要删除所选的评论吗?")) {
                var id = $(this).attr("data-value");
                var self = $(this);
                $.ajax({
                    url: '<?php echo $this->createUrl("deletecomment"); ?>',
                    data: {'id': id},
                    type: "POST",
                    dataType: "html",
                    success: function(data) {
                        if (data == "ok") {
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
        $(document).delegate("#userinfo-comment-list a[data-name='delete-reply']", 'click', function() {
            if (window.confirm("确定要删除所选的回复吗?")) {
                var id = $(this).attr("data-value");
                var self = $(this);
                $.ajax({
                    url: '<?php echo $this->createUrl("deletereply"); ?>',
                    data: {'id': id},
                    type: "POST",
                    dataType: "html",
                    success: function(data) {
                        if (data == "ok") {
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
        $(document).delegate("#userinfo-comment-list .comment-reply", 'mouseenter', function() {
            $(this).find("a[data-name='reply-comment']").show();
            $(this).find("a[data-name='delete-reply']").show();
        });
        $(document).delegate("#userinfo-comment-list .comment-reply", 'mouseleave', function() {
            $(this).find("a[data-name='reply-comment']").hide();
            $(this).find("a[data-name='delete-reply']").hide();
        });
        $("#visitedHistory [action-type='delVisit']").on('click', function() {
            var self = $(this);
            var id = self.attr("action-data-id");
            if (window.confirm("确定要删除该访问记录？")) {
                $.ajax({
                    url: deleteVisitUrl,
                    data: {'id': id},
                    type: "POST",
                    success: function(data) {
                        if (data == "ok") {
                            $.fn.yiiListView.update("visitedHistory");
                        } else {
                            alert("共享操作失败，请联系管理员");
                        }

                    }
                });
                return true;
            } else {
                return false;
            }
            return false;
        });
         $(document).delegate("a[name='agreeeAnswer']", 'click', function() {
            var self = $(this);
            var pk_id = self.attr("data-pk");
            var model = self.attr("data-model");
            $.post(agreeVoteUrl, {'pk_id': pk_id,'model':model}, function(data) {
                if (data.message == "ok") {
                    self.toggleClass("active");
                    self.parent().find(".icon-arrow-down").parent().removeClass("active");
                    self.find(".count").text(data.count);
                    if (self.attr("data-original-title") == "赞同") {
                        self.attr("data-original-title", "取消赞同");
                        self.parent().find(".icon-arrow-down").parent().attr("data-original-title", "反对");
                        var someVotes = self.parents(".vote-number").next().find(".vote-info");
                        var aHtml = "<a href='javascript:;' class='user-label' data-value='<?php echo Yii::app()->user->id ?>' data-id='<?php echo Yii::app()->user->id ?>'><?php echo User::getNameById(Yii::app()->user->id); ?></a>";
                        if (someVotes.find("a[data-value='" +<?php echo Yii::app()->user->id; ?> + "']").length == 0) {
                            if (someVotes.find("a").length == 0) {
                                someVotes.text('');
                                someVotes.append(aHtml);
                                someVotes.append(" <a href='javascript:;' class='some'>赞同</a>");
                            } else {
                                someVotes.find('.vote-some').append(aHtml + "<span>、</span>");
                                someVotes.find('.vote-some').find("a[data-value='" +<?php echo Yii::app()->user->id; ?> + "']").next().insertBefore(someVotes.find('.vote-some').find("a:eq(0)"));
                                someVotes.find('.vote-some').find("a[data-value='" +<?php echo Yii::app()->user->id; ?> + "']").insertBefore(someVotes.find('.vote-some').find("a:eq(0)").prev());
                                someVotes.find('.vote-all').append(aHtml + "<span>、</span>");
                                someVotes.find('.vote-all').find("a[data-value='" +<?php echo Yii::app()->user->id; ?> + "']").next().insertBefore(someVotes.find('.vote-all').find("a:eq(0)"));
                                someVotes.find('.vote-all').find("a[data-value='" +<?php echo Yii::app()->user->id; ?> + "']").insertBefore(someVotes.find('.vote-all').find("a:eq(0)").prev());
                            }
                        }
                    } else {
                        self.attr("data-original-title", "赞同");
                        var someVotes = self.parents(".vote-number").next().find(".vote-info");
                        var userId = someVotes.find('.vote-some').length > 0 ? someVotes.find('.vote-some').find("a[data-value='" +<?php echo Yii::app()->user->id; ?> + "']") : someVotes.find("a[data-value='" +<?php echo Yii::app()->user->id; ?> + "']");
                        if (userId.next().html() == "、") {
                            userId.next().remove();
                        }
                        userId.remove();
                        if (someVotes.find('.vote-all').length > 0) {
                            var allUserId = someVotes.find('.vote-all').find("a[data-value='" +<?php echo Yii::app()->user->id; ?> + "']");
                            if (allUserId.next().html() == "、") {
                                allUserId.next().remove();
                            }
                            allUserId.remove();
                        }
                        if (data.count == 0) {
                            someVotes.empty();
                        }
                    }

                } else {
                    alert('操作失败');
                }
            }, 'json');
        });
        $(document).delegate("a[name='disagreeeAnswer']", 'click', function() {
            var self = $(this);
            var pk_id = self.attr("data-pk");
            var model = self.attr("data-model");
            $.post(disagreeVoteUrl, {'pk_id': pk_id,'model':model}, function(data) {
                if (data.message == "ok") {
                    self.toggleClass("active");
                    self.parent().find(".icon-arrow-up").parent().removeClass("active");
                    self.parent().find(".icon-arrow-up").parent().find(".count").text(data.count);
                    if (self.attr("data-original-title") == "取消反对") {
                        self.attr("data-original-title", "反对，不会显示你的姓名");
                    } else {
                        self.attr("data-original-title", "取消反对");
                        self.parent().find(".icon-arrow-up").parent().attr("data-original-title", "赞同");
                        var someVotes = self.parents(".vote-number").next().find(".vote-info");
                        var userId = someVotes.find('.vote-some').length > 0 ? someVotes.find('.vote-some').find("a[data-value='" +<?php echo Yii::app()->user->id; ?> + "']") : someVotes.find("a[data-value='" +<?php echo Yii::app()->user->id; ?> + "']");
                        if (userId.next().html() == "、") {
                            userId.next().remove();
                        }
                        userId.remove();
                        if (someVotes.find('.vote-all').length > 0) {
                            var allUserId = someVotes.find('.vote-all').find("a[data-value='" +<?php echo Yii::app()->user->id; ?> + "']");
                            if (allUserId.next().html() == "、") {
                                allUserId.next().remove();
                            }
                            allUserId.remove();
                        }
                        if (data.count == 0) {
                            someVotes.empty();
                        }
                    }
                } else {
                    alert('操作失败');
                }
            }, 'json');
        });
    });
</script>