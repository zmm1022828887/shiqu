<style>
    /*主题样式*/
    .content{margin: 0 auto; width: 1030px; position: relative;}
    .content legend{border-bottom:none;}
    /*左侧样式*/
    .content .main-content {float: left;width:100%;}
    .content .grid-view{padding-top: 0;}
    .content .main-content .content-left{width: 690px; float: left;}
    /*右侧样式*/
    .content .content-right{width: 310px; float: right;}
    .content .content-right .content-sidebar h5{text-align: left;padding: 0; margin: 0;}
    .content .content-right .content-sidebar .sidebar-top, .content .content-right .content-sidebar .sidebar-center{border-bottom: 1px solid #ccc;padding:0 0 15px;}
    .content .content-right .content-sidebar .topic-title{font-size: 16px;}
    .content .content-right .content-sidebar .sidebar-top div{display: inline-block;}
    .content .content-right .content-sidebar .sidebar-top a.btn{margin-right: 10px;}
    .content .content-right .content-sidebar .member-list ul {margin-top: -20px;letter-spacing: -0.31em;word-spacing: -0.43em;font-size: 0;}
    .content .content-right .content-sidebar .member-list li {display: inline-block;zoom: 1; width: 75px;margin-top: 20px;text-align: center;font-size: 12px;vertical-align: top;letter-spacing: normal; word-spacing: normal;}
    .content .content-right .content-sidebar .member-list .pic {margin-bottom: 5px;}
    .content .content-right .content-sidebar .member-list .name {clear: both;padding: 0 4px; word-wrap: break-word; word-break: normal;}
    .main-content .topic-header-left{float: left;margin-right: 10px;}

</style>
<?php
$this->pageTitle = $topicModel->name . " - " . Yii::app()->name;
$this->widget('bootstrap.widgets.TbBreadcrumbs', array('links' =>  array("管理")));
?>
<div class="content clearfix" style="padding-top:18px;">
    <div class="main-content">
        <div class="content-left">
            <div class="topic-info">
                <div class="topic-name zm-editable-status-normal" id="topic-title">
                    <div class="topic-header-left">
                        <a href="javascript:;"><img height="50" width="50" src="<?php echo $this->createUrl("getimage", array("id" => $topicModel->id, "type" => "topic")); ?>" alt="<?php echo $topicModel->name; ?>"></a>
                    </div>
                    <div class="topic-header-right">
                        <h3 style="font-size:18px;vertical-align: text-top;height: 30px;line-height: 30px;"><?php echo $topicModel->name; ?></h3>
                        <?php $count = LoveTopic::model()->count("create_user=:create_user and topic_id=:topic_id", array(":create_user" => Yii::app()->user->id, ":topic_id" => $topicModel->id)) ?>
                        <a href="javascript:;" title="<?php echo ($count > 0) ? '取消固定' : '固定话题'; ?>" style="color:#ccc" data-topicid="<?php echo $topicModel->id; ?>" name="<?php echo (Yii::app()->user->isGuest) ? 'noLogin' : 'loveTopic'; ?>"><i class="icon-pushpin"></i><?php echo ($count > 0) ? '取消固定' : '固定话题'; ?></a>
                    </div>
                </div>
            </div>
            <?php
            $type = isset($_GET["type"]) ? $_GET["type"] : "question";
            $this->widget('bootstrap.widgets.Tbtabs', array(
                'type' => 'tabs', // '', 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
                'htmlOptions' => array('style' => 'margin-top:10px;', 'class' => 'vote-tabs'),
                'tabs' => array(
                    array('label' => '最新问题', 'content' => $this->renderPartial('../_questiontabs', array("type" => "new"), true), 'active' => $type == "question" ? true : false),
                    array('label' => '最新文章', 'content' => $this->renderPartial('../_articletabs', array("type" => "new"), true), 'active' => $type == "article" ? true : false),
                    array('label' => '关注者', 'content' => $this->renderPartial('../_followeeslist', array("type" => "followees", 'followees' => $topicModel->join_user), true), 'active' => $type == "user" ? true : false),
                )
                    )
            );
            ?>
        </div>
        <div class="content-right">

            <div class="content-sidebar">
                <?php if (Yii::app()->user->isGuest) { ?>
                    <div class="alert alert-info"><?php echo Sys::model()->find()->site_desc; ?></div>
                <?php } ?>
                <div class="sidebar-top">
                    <?php $this->widget('bootstrap.widgets.TbButton', array('url' => 'javascript:;', 'label' => in_array(Yii::app()->user->id, explode(",", trim($topicModel->join_user, ","))) ? '取消关注' : '关注', 'size' => 'big', 'type' => 'success', 'htmlOptions' => array('name' => Yii::app()->user->isGuest ? 'noLogin' : 'joinTopic', 'data-topicid' => $topicModel->id))); ?>
                    <div class="sidebar-left">
                        <a href="<?php echo $this->createUrl("/default/groupuser/", array("group_id" => $topicModel->id)); ?>" title="查看 <?php echo $topicModel->name; ?> 全部人员"><strong id="joinTotal"><?php echo $topicModel->join_user == "" ? 0 : count(explode(",", trim($topicModel->join_user, ","))); ?></strong></a> 人关注了该话题
                    </div>

                </div>
                <div class="sidebar-center">
                    <h3 class="topic-title">描述</h3>
                    <div class="topic-desc"><?php echo $topicModel->desc; ?></div>
                </div>
                <?php if ($topicModel->parent_id != 0) { ?>
                    <div class="sidebar-center">
                        <h3 class="topic-title">父话题</h3>
                        <div class="topic-desc">
                            <a  class="topic-label" href="javascript:;" data-id="<?php echo $topicModel->parent_id; ?>"><span class="badge"><?php echo Topic::model()->findByPk($topicModel->parent_id)->name; ?></span> </a>
                        </div>
                    </div>
                <?php } ?>
                <?php if (Topic::model()->count("parent_id=:parent_id", array(":parent_id" => $topicModel->id)) > 0) { ?>
                    <div class="sidebar-center">
                        <h3 class="topic-title">子话题</h3>
                        <div class="topic-desc">
                            <?php $childrenModel = Topic::model()->findAll("parent_id=:parent_id", array(":parent_id" => $topicModel->id)); ?>
                            <?php foreach ($childrenModel as $key => $value) { ?>
                                <a class="topic-label"  href="javascript:;" data-id="<?php echo $value->id; ?>"><span class="badge"><?php echo $value->name; ?></span></a> 
                            <?php } ?>

                        </div>
                    </div>
                <?php } ?>
                <div class="sidebar-center">
                    <h3 class="topic-title topic-user">最近关注</h3>

                    <div class="member-list" id="topicuser-list">

                        <ul> 
                            <?php if ($topicModel->join_user != "") { ?>
                                <?php $userId = array_reverse(explode(",", trim($topicModel->join_user, ","))); ?>
                                <?php
                                for ($i = 0; $i < count($userId); $i++) {
                                    if ($i == 8)
                                        break;
                                    ?>
                                    <li data-uid="<?php echo $userId[$i]; ?>">
                                        <div class="pic">
                                            <a  class="user-label clearfix" href="javascript:;" data-id="<?php echo $userId[$i]; ?>">
                                                <img height="50" width="50"  src="<?php echo $this->createUrl("getimage", array("id" => $userId[$i], "type" => "avatar")); ?>" alt="<?php echo User::getNameById($userId[$i]) . " - " . $this->title; ?>"> 
                                            </a>
                                        </div>
                                        <div class="name">
                                            <a  title="<?php echo User::getNameById($userId[$i]) . " - " . $this->title; ?>" target="_blank" href="<?php echo $this->createUrl('default/userinfo', array('user_id' => $userId[$i])); ?>"><?php echo User::getNameById($userId[$i]); ?></a>
                                        </div>
                                    </li>
                                <?php } ?>
                            <?php } ?>
                        </ul>   
                    </div>
                </div> 
            </div>
        </div>
    </div>
</div>