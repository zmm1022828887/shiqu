<style>
    .main{width: 1030px;margin:0 auto;padding: 25px 0 50px;}
    .main .main-left{width:780px; float: left;padding-bottom: 10px; }
    .main  .main-left .profile-info {border: 1px solid #ddd;border-radius: 4px;box-shadow: 0 1px 0 #f2f4f5;} 
    .main  .main-left .profile-info  .profile-header-main{padding: 12px 18px;}
    .main  .main-left .profile-info .top{font-size: 1.4em;margin-bottom: 10px;}
    .main  .main-left .profile-info .name{ font-weight: 700;color: #222;}
    .profile-header-info{padding-left: 4px;width:628px;}
    .profile-header-info td,.profile-header-info th{padding: 4px 6px;}
    .profile-header-operation {padding: 12px 18px;border-top: 1px dotted #eee;height: 24px;}
    .profile-header-op-btns{float: right;}
    .profile-section-wrap{margin-top: 25px;border: 1px solid #ddd;overflow: visible;border-radius: 4px;box-shadow: 0 1px 0 #f2f4f5;}
    .profile-section-wrap legend{padding: 7px 14px;font-size: 14px;height: 26px;line-height: 26px;overflow: hidden;}
    .profile-section-wrap form{padding: 10px;}
    .profile-section-wrap .grid-view,.profile-section-wrap .list-view,.profile-section-wrap .alert-info {padding: 7px 14px;margin: 0 auto 10px;}
    .main  .main-left .profile-navbar {background-color: #fcfcfc;border-top: 1px solid #ddd;box-shadow: 0 2px 2px #f0f0f0 inset;border-radius: 0 0 4px 4px;}
    .main  .main-left .profile-navbar .item {float: left;padding: 12px 20px;font-weight: 700;color: #666;text-align: center;font-size: 14px;text-decoration: none;line-height: 22px;}
    .main  .main-left  .profile-navbar .item.home.active {border-color: #ddd;color:#ccc;}
    .main  .main-left  .profile-navbar .item.home{border-right: 1px solid #eee;}
    .main  .main-left  .profile-navbar .item.active {color: #222;border: solid #ddd;border-width: 0 1px;background-color: #f7f7f7;box-shadow: 0 2px 2px #f0f0f0 inset;}
    .main .main-left .item .num{color:#ff0000;}
    .main  ul{padding-left: 0px;padding-right: 0px;}
    .main  .item-category a{color:#666666;margin: 0;padding-left:20px;padding-right: 0px;}
    .main .main-right{width:234px; float: right;}
    .main .main-right .profile-side-following,.main .main-right .profile-side-section{border-bottom: 1px solid #eee;margin-bottom: 15px;}
    .main .main-right .side-section-inner{margin-bottom: 8px;padding-bottom: 8px;}
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
    .visitor-count {margin: 10px 9px 0 0;padding: 15px 4px 4px 4px;}
    .visitor-count li {float: left;position: relative;text-align: center;}
    .visitor-count li a {display: inline-block;zoom: 1;margin-top: 4px;}
    .visitor-count li.gap {padding-left: 10px;margin-left: 15px;}
    .user-list{margin-left:4px;display: inline-block;text-align: center;}
    .user-list .user-name{position:absolute;bottom:0px;right:0px;display:inline-block;width: 70px;word-wrap: break-word;overflow: hidden;height: 20px;line-height: 20px;}
    .user-list .remove-visit{position:absolute;top:0px;right:0px;vertical-align: top;height: 12px;width: 12px;line-height: 12px;font-size: 12px;text-align: center;text-decoration: none;border-radius: 2px;display: block;color: #FFFFFF;background-color: #49afcd;visibility: hidden;}
    .user-list:hover .remove-visit{ visibility: visible !important;}
</style>
<?php
$userModel = User::model()->findByPk(Yii::app()->user->id);

?>
<?php $this->pageTitle = $userModel->user_name . "我的主页 - " . Yii::app()->name; ?>
<?php
$privArray = unserialize($userModel->priv);
$userModel->visit_priv = $privArray['visit_priv'];
$countArray = unserialize($userModel->visit_count);
$time = strtotime(date("Y-m-d", time()));
$todayCount = Visit::model()->count("to_user = " . $userModel->id . " and create_time > " . $time);
$userModel->visit_count = $countArray['visit_count'] ? $countArray['visit_count'] : 0;
$userModel->refuse_count = $countArray['refuse_count'] ? $countArray['refuse_count'] : 0;
?>
<div class="main clearfix">
    <div class="main-left">
        <div class="profile-info private_list" data-uid="<?php echo $userModel->id; ?>" data-username="<?php echo $userModel->user_name; ?>">
            <div class="profile-header-main">
                <div class="avatar_info clearfix">
                    <div class="top">

                        <div class="title-section ellipsis">
                            <a class="name" href="javascript:;"><?php echo User::getNameById($userModel->id); ?></a>
                            <?php if ($userModel->signature != "") { ?>，<span class="bio" title="<?php echo $userModel->signature; ?>"><?php echo $userModel->signature; ?></span>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="body">
                        <div id="avatar" style="float: left;">
                            <img height="100" width="100" alt="<?php echo User::getNameById($userModel->id); ?>" src="<?php echo $this->createUrl("/default/getimage", array("id" => $userModel->id, "type" => "avatar")); ?>">
                        </div>
                        <div class="profile-header-info pull-left">
                            <table style="width:100%;"><tbody><tr class="odd"><th style='width: 10px;'><i class="icon-male"></i></th><td><?php echo ($userModel->gender = 1) ? "男" : "女"; ?><a href="<?php echo ($_GET["type"] == "about") ? $this->createUrl("default/personal", array("user_id" => $userModel->id)) : $this->createUrl("default/personal", array("type" => "about", "user_id" => $userModel->id)); ?>" class="pull-right"><i class='<?php echo ($_GET["type"] == "about") ? "icon-arrow-left-4" : "icon-arrow-right-5"; ?>'></i> <?php echo ($_GET["type"] == "about") ? "返回个人主页" : "查看详细资料"; ?></a></td></tr>
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
                    <i class="icon-thumbs-up"></i>  <span style="color:#ff0000"><?php echo Vote::model()->count("to_user=:to_user and opinion=0", array(":to_user" => $userModel->id)); ?> </span> 赞同
                    <a style="color:#666;;margin-left:10px;" class="item <?php echo ($_GET["type"] == "wealth") ? "active" : ""; ?>" href="<?php echo $this->createUrl("default/personal", array("type" => "wealth", "user_id" => $userModel->id)); ?>">

                        <i class="icon-coins"></i> <span class="num"><?php echo $userModel->wealth; ?></span> 财富值
                    </a>
                </div>
                <div class="profile-header-op-btns clearfix">
                    <a  class="btn btn-link" style="padding:0;" href="<?php echo $this->createUrl("personal", array("type" => "user")); ?>"><i class="icon-pencil"></i> 编辑我的资料</a>
                </div>
            </div>
            <div class="profile-navbar clearfix">
                <a class="item home first <?php echo (!isset($_GET["type"])) ? "active" : ""; ?>" href="<?php echo $this->createUrl("default/personal", array("user_id" => $userModel->id)); ?>">
                    <i class="icon icon-home"></i><span class="hide-text">主页</span>
                </a>
                <a class="item <?php echo ($_GET["type"] == "personal") ? "active" : ""; ?>"  href="<?php echo $this->createUrl("personal", array("type" => "personal")); ?>">
                    关注人动态
                </a>
                <a class="item <?php echo ($_GET["type"] == "question") ? "active" : ""; ?>" href="<?php echo $this->createUrl("personal", array("type" => "question")); ?>">
                    问题
                    <span class="num"><?php echo Question::model()->count("create_user=" . $userModel->id); ?></span>
                </a>
                <a class="item <?php echo ($_GET["type"] == "answer") ? "active" : ""; ?>" href="<?php echo $this->createUrl("personal", array("type" => "answer")); ?>">
                    回答
                    <span class="num"><?php echo Answer::model()->count("create_user=" . $userModel->id); ?></span>
                </a>
                <a class="item <?php echo ($_GET["type"] == "topic") ? "active" : ""; ?>" href="<?php echo $this->createUrl("personal", array("type" => "topic")); ?>">
                    话题
                    <span class="num"><?php echo Topic::model()->count("create_user=" . $userModel->id); ?></span>
                </a>
                <a class="item <?php echo ($_GET["type"] == "article") ? "active" : ""; ?>" href="<?php echo $this->createUrl("personal", array("type" => "article")); ?>">
                    文章
                    <span class="num"><?php echo Article::model()->count("create_user=" . $userModel->id); ?></span>
                </a>

            </div>
        </div>
        <?php if ((!isset($_GET["type"])) && ($userModel->topic_status == 1)) { ?>
            <div class="profile-section-wrap skilled-topics clearfix"> 
                <fieldset style="overflow: hidden;">
                    <legend><b>你</b>擅长的话题</legend>
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
                                        <span><a href="javascript:;" title="你在话题<?php echo $skillTopicModel->name; ?>获得赞"><i class="icon-thumbs-up"></i><?php echo User::getTotalByTypeId($skillTopicModel->id, "disagree");?></a></span>
                                        <span><a href="<?php echo $this->createUrl("personal", array("type" => "skilltopic", "id" => $skillTopicModel->id, "name" => "answer")); ?>" title="你在<?php echo $skillTopicModel->name; ?>下的回答"><i class="icon-bubble"></i><?php echo User::getTotalByTypeId($skillTopicModel->id, "answer");?></a></span>
                                        <span><a href="<?php echo $this->createUrl("personal", array("type" => "skilltopic", "id" => $skillTopicModel->id, "name" => "article")); ?>" title="你在<?php echo $skillTopicModel->name; ?>下发表的文章"><i class="icon-file-4"></i><?php echo User::getTotalByTypeId($skillTopicModel->id, "article");?></a></span>
                                        <span><a href="<?php echo $this->createUrl("personal", array("type" => "skilltopic", "id" => $skillTopicModel->id, "name" => "question")); ?>" title="你在<?php echo $skillTopicModel->name; ?>下的提问"><i class="icon-question"></i><?php echo User::getTotalByTypeId($skillTopicModel->id, "question");?></a></span> 
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
        <div class="profile-section-wrap clearfix">
            <?php if (!isset($_GET["type"]) || ($_GET["type"] == "personal")) { ?>  

                <fieldset style="overflow: hidden;">
                    <legend style="margin-bottom:10px;"><b><?php echo ($_GET["type"] == "personal") ? "关注人" : "你"; ?></b>最新动态
                        <?php
                        if (!isset($_GET["type"]))
                            $this->widget('bootstrap.widgets.TbButton', array(
                                'label' => '访问权限设置',
                                'size' => 'small',
                                'icon' => 'icon-cog',
                                'type' => 'info',
                                'htmlOptions' => array("class" => "pull-right", "style" => "margin-right:20px;", 'onclick' => '$("#privVisit").modal({"backdrop": "static", "show": true});')
                            ));
                        ?></legend> 
                </fieldset>

                <?php
                $criteria = new CDbCriteria;

                if ($_GET["type"] == "personal") {
                    if (Yii::app()->user->name != "admin") {
                        $criteria->addNotInCondition("notification_type", array("report", "reportanswer", "reportquestion", "reportarticle"));
                    }
                    $criteria->addInCondition("from_id", ($userModel->followees == "") ? array() : explode(",", trim($userModel->followees, ",")));
                } else {
                    $criteria->addCondition("from_id ='" . $userModel->id . "'");
                }
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
            <?php } elseif ($_GET["type"] == "topic") {
                ?>  
                <fieldset style="overflow: hidden;">
                    <legend style="margin-bottom:10px;">话题管理</legend> 
                </fieldset>

                <?php $this->renderPartial('../_topicgrid'); ?>
            <?php } elseif ($_GET["type"] == "question") {
                ?>  
                <fieldset style="overflow: hidden;">
                    <legend style="margin-bottom:10px;">问题管理</legend> 
                </fieldset>

                <?php $this->renderPartial('../_questiongrid'); ?>
            <?php } elseif ($_GET["type"] == "skilltopic") { 
                
               $skillTopicModels = Topic::model()->findByPk($_GET["id"]);
                ?>  
                <fieldset>
                    <legend style="margin-bottom:10px;">
                        <b>你</b> 在 <b><a href="<?php echo $this->createUrl("topic",array("id"=>$skillTopicModels->id));?>"><?php echo $skillTopicModels->name;?></a></b> 话题下的
                        <?php echo $_GET["name"]=="answer" ? "回答": ($_GET["name"]=="question" ? "提问":"发表的文章");?>
                        <?php echo "（".User::getTotalByTypeId($skillTopicModels->id, $_GET["name"])."）"?>
                    </legend> 
                </fieldset>
                <?php if($_GET["name"]=="answer"){
                 $criteria = new CDbCriteria;
                $criteria->addCondition("create_user=" . Yii::app()->user->id);
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
                <?php $this->renderPartial('../_questiongrid'); ?>
                <?php }else{?>
             <?php $this->renderPartial('../_articlegrid'); ?>
              <?php }?>
           <?php } elseif ($_GET["type"] == "answer") {
                ?>  
                <fieldset style="overflow: hidden;">
                    <legend style="margin-bottom:10px;">回答管理</legend> 
                </fieldset>
                <?php
                $criteria = new CDbCriteria;
                $criteria->addCondition("create_user=" . Yii::app()->user->id);
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
                    'id' => 'answerListView',
                    'htmlOptions' => array('style' => 'padding-top:0px')
                ));
                ?> 
            <?php } elseif ($_GET["type"] == "article") {
                ?>  
                <fieldset style="overflow: hidden;">
                    <legend style="margin-bottom:10px;">文章管理</legend> 
                </fieldset>

                <?php $this->renderPartial('../_articlegrid'); ?>
            <?php } elseif ($_GET["type"] == "followees") {
                ?>  
                <fieldset style="overflow: hidden;">
                    <legend style="margin-bottom:10px;"><b>你</b> 关注了<?php echo $userModel->followees == '' ? 0 : count(explode(",", trim($userModel->followees, ","))); ?>个人</legend> 
                </fieldset>
                <?php $this->renderPartial('../_followeeslist', array('type' => 'followees', 'followees' => $userModel->followees)); ?>
            <?php } else if ($_GET["type"] == "user") {
                ?>
                <fieldset style="overflow: hidden;">
                    <legend style="margin-bottom:10px;">基本信息</legend>
                </fieldset>
                <?php
                $this->renderPartial('../_personInfo', array("userModel" => $userModel, 'successMessage' => $successMessage));
            } elseif ($_GET["type"] == "followers") {
                ?>  
                <fieldset style="overflow: hidden;">
                    <legend style="margin-bottom:10px;"><b>你</b> 被<?php echo $userModel->followers == '' ? 0 : count(explode(",", trim($userModel->followers, ","))); ?>人关注</legend> 
                </fieldset>
                <?php $this->renderPartial('../_followeeslist', array('type' => 'followers', 'followers' => $userModel->followers)); ?>
            <?php } elseif ($_GET["type"] == "jointopic") { ?>  
                <fieldset style="overflow: hidden;">
                    <?php $userCount = Topic::model()->count("join_user like '%," . $userModel->id . ",%'"); ?>
                    <legend style="margin-bottom:10px;"><b>你</b> 关注了 <?php echo $userCount; ?> 个话题<?php
                        $this->widget('bootstrap.widgets.TbButton', array(
                            'label' => '新建话题',
                            'buttonType' => 'button',
                            'icon' => 'icon-pencil',
                            'type' => 'info',
                            'size' => 'small',
                            'htmlOptions' => array("name" => "CreteTopic", 'style' => 'margin-right:20px;', 'class' => 'pull-right'),
                        ));
                        ?></legend> 
                </fieldset>
                <?php $this->renderPartial('../_jointopiclist'); ?>
            <?php } elseif ($_GET["type"] == "wealth") {
                ?>  
                <fieldset style="overflow: hidden;">
                    <legend style="margin-bottom:10px;"><b>你</b> 财富值的详细记录</legend> 
                </fieldset>
                <?php
                $criteria = new CDbCriteria;
                $criteria->addCondition("create_user ='" . $userModel->id . "'");
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
            <?php } elseif ($_GET["type"] == "about") { ?>
                <fieldset style="overflow: hidden;">
                    <legend style="margin-bottom:10px;"><b>你</b> 的详细信息</legend>      
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
            <a class="item" href="<?php echo $this->createUrl("personal", array("type" => "followees", "user_id" => $userModel->id)); ?>">
                <span class="gray-normal">关注了</span><br>
                <strong><?php echo $userModel->followees == '' ? 0 : count(explode(",", trim($userModel->followees, ","))); ?></strong><label> 人</label>
            </a>
            <a class="item" href="<?php echo $this->createUrl("personal", array("type" => "followers", "user_id" => $userModel->id)); ?>"  style="border-left: 1px solid #eee;padding-left:20px;">
                <span class="gray-normal">关注者</span><br>
                <strong><?php echo $userModel->followers == '' ? 0 : count(explode(",", trim($userModel->followers, ","))); ?></strong><label> 人</label>
            </a>
        </div>
        <div class="profile-side-section">
            <div class="side-section-inner clearfix">
                <?php $userCount = Topic::model()->count("join_user like '%," . $userModel->id . ",%'"); ?>
                <div class="profile-side-section-title">
                    关注了 <a href="<?php echo $this->createUrl("personal", array('type' => 'jointopic', 'user_id' => $userModel->id)); ?>" class="link-litblue"><strong><?php echo $userCount; ?> 个话题</strong></a>
                </div>
                <?php if ($userCount > 0) { ?>
                    <div class="profile-side-columns">
                        <?php
                        $criteria = new CDbCriteria;
                        $criteria->addSearchCondition('join_user', "," . trim($userModel->id) . ",");
                        $topicModel = Topic::model()->findAll($criteria);
                        $i = 0;
                        foreach ($topicModel as $topic) {
                            $i++;
                            if ($i == 7)
                                break
                                ?>
                            <div>
                                <a href="<?php echo $this->createUrl("topic", array("id" => $topic->id)); ?>" class="link topic-label"  data-id="<?php echo $topic->id; ?>">  <img alt="<?php echo $topic->name; ?>" class="avatar"  src="<?php echo $this->createUrl("getimage", array("id" => $topic->id, "type" => "topic")); ?>"></a>
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

        <?php
        $this->widget('bootstrap.widgets.Tbtabs', array(
            'type' => 'pills', // '', 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
            'htmlOptions' => array('id' => 'visit-tab'),
            'tabs' => array(
                array('label' => '谁看过我', 'content' => $this->renderPartial('../_visituser', array("type" => "visited", "user_id" => Yii::app()->user->id), true), 'active' => true),
                array('label' => '我看过谁', 'content' => $this->renderPartial('../_visituser', array("type" => "visit", "user_id" => Yii::app()->user->id), true), true),
                array('label' => '被挡访客', 'content' => $this->renderPartial('../_visituser', array("type" => "refuse", "user_id" => Yii::app()->user->id), true), true),
            )
                )
        );
        ?>
        <div class="profile-side-section" style="border-bottom: none;border-top:1px solid #ccc;">
            <div class="side-section-inner">
                <ul class="visitor-count  clearfix">
                    <li><p><span>今日浏览</span> </p><a href="javascript:;" title="今天浏览量：<?php echo $todayCount; ?>"><?php echo $todayCount; ?></a></li>
                    <li class="gap"><p><span>总浏览</span> </p><a href="javascript:;" title="总浏览量：<?php echo $userModel->visit_count; ?>"><?php echo $userModel->visit_count; ?></a></li>
                    <li class="gap " id="today_refuse_visitor"><p><span>被挡访客</span></p><a href="javascript:;" title="被挡访问量：<?php echo $userModel->refuse_count; ?>"><?php echo $userModel->refuse_count; ?></a></li>
                </ul>
            </div>
        </div>
    </div>
</div>
<?php $this->beginWidget('bootstrap.widgets.TbModal', array('id' => 'privVisit', 'options' => array("backdrop" => "static"))); ?>
<div class="modal-header">
    <a class="close" data-dismiss="modal">×</a>
    <h4>访问权限设置</h4> 
</div>
<div class="modal-body">
    <div class="form">
        <?php
        $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
            'id' => 'priv-visit-form',
            'type' => 'horizontal',
            'action' => $this->createUrl("privvisit"),
        ));
        ?>
        <?php echo $form->dropDownListRow($userModel, 'visit_priv', User::model()->getPriv(), array('id' => 'privVisitPriv')); ?>
        <?php $this->endWidget(); ?>
    </div><!-- form -->
</div>
<div class="modal-footer" style="text-align: right;">
    <?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'button',
        'type' => 'info',
        'label' => '确定',
        'htmlOptions' => array("onclick" => "js:$('#priv-visit-form').submit()"),
    ));
    ?>
    &nbsp; &nbsp; &nbsp;
    <?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'button',
        'label' => '关闭',
        'htmlOptions' => array("data-dismiss" => "modal"),
    ));
    ?>
</div>
<?php $this->endWidget() ?>

<script>
    var deleteDescUrl = "<?php echo Yii::app()->controller->createUrl("deletedesc"); ?>"; //删除描述ction
    var deleteVisitUrl = "<?php echo Yii::app()->controller->createUrl("deletevisit"); ?>"; //删除描述ction
    var isLogin = "<?php echo Yii::app()->user->isGuest ? "false" : "true"; ?>";
    var agreeVoteUrl = "<?php echo $this->createUrl("agreeavote"); ?>";
    var disagreeVoteUrl = "<?php echo $this->createUrl("disagreevote"); ?>";
    var changeCommentUrl = "<?php echo $this->createUrl("changecomment"); ?>";
    function report(pk, type) {
        if (isLogin == "false") {
            $("[name='noLogin']").trigger("click");
            return false;
        }
        $('#reportModal').modal('show');
        $('#reportModal').find(".modal-title").text(type == "question" ? "为什么举报该问题？" : "为什么举报该回答？");
        $("#Message_report_content").hide();
        $("#Message_report_uid").val(pk);
        $("#Message_report_model").val(type);
        return false;
    }
    function changeComment(answerId, type) {
        $.post(changeCommentUrl, {'answerId': answerId, 'type': type}, function(data) {
            if (data == "ok") {
                $.fn.yiiListView.update("answerListView");
            } else {
                alert("删除失败");
            }
        });
    }
</script>
<script>
    $(document).ready(function() {
        $(document).delegate("#user-comment-list a[data-name='reply-comment']", 'click', function() {
            var userId = $(this).attr("user-value");
            var commentId = $(this).attr("data-value");
            var userName = $(this).attr("name-value");
            var page = $(this).attr("data-page");
            if ($("#createDiaryReply").length > 0) {
                $("#createDiaryReply").remove();
            }
            ;
            form = $("<form id='createDiaryReply' style='position:relative;right:0;padding:10px;' class='form well'></form>");
            form.attr("action", '<?php echo $this->createUrl("createreply", array("type" => "comment")); ?>');
            form.attr("method", "POST");
            user = $("<div style='font-size:14px;'><b>@ " + userName + "</b> :</div>");
            form.append(user);
            userIuput = $("<input type='hidden' name='user_id'/>");
            userIuput.attr("value", userId);
            form.append(userIuput);
            commentInput = $("<input type='hidden' name='comment_id'/>");
            commentInput.attr("value", commentId);
            form.append(commentInput);
            commentType = $("<input type='hidden' name='type' value='comment' />");
            form.append(commentType);
            content = $("<textarea name='content' class='content' style='width:78%;margin-bottom:0;resize:none;'></textarea>");
            form.append(content);
            pageInput = $("<input type='hidden' name='page'/>");
            pageInput.attr("value", page);
            form.append(pageInput);
            submit = $('<button class="btn" type="button" id="createDiarySubmit" style="margin-left:10px;">发表</button>');
            form.append(submit);
            form.insertAfter($(this).parent());
            return false;
        });
        $(document).delegate("#user-comment-list a[data-name='delete-comment']", 'click', function() {
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
        $(document).delegate("#user-comment-list a[data-name='delete-reply']", 'click', function() {
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
        $(document).delegate("#user-comment-list .comment-reply", 'mouseenter', function() {
            $(this).find("a[data-name='reply-comment']").show();
            $(this).find("a[data-name='delete-reply']").show();
        });
        $(document).delegate("#user-comment-list .comment-reply", 'mouseleave', function() {
            $(this).find("a[data-name='reply-comment']").hide();
            $(this).find("a[data-name='delete-reply']").hide();
        });
        $(document).delegate("#user-comment-list #createDiarySubmit", 'click', function() {
            if ($.trim($(this).parents("#createDiaryReply").find(".content").val()) == "") {
                alert("请输入回复内容");
            } else {
                $("#createDiaryReply").submit();
            }
        });


        $("#visit-tab [action-type='delVisit']").live('click', function() {
            var self = $(this);
            var id = self.attr("action-data-id");
            if (window.confirm("确定要删除该访问记录？")) {
                $.ajax({
                    url: deleteVisitUrl,
                    data: {'id': id},
                    type: "POST",
                    success: function(data) {
                        if (data == "ok") {
                            var ajaxId = self.parents(".list-view").attr("id");
                            $.fn.yiiListView.update(ajaxId);
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
            $.post(agreeVoteUrl, {'pk_id': pk_id, 'model': model}, function(data) {
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
            $.post(disagreeVoteUrl, {'pk_id': pk_id, 'model': model}, function(data) {
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