<style>
    .profile-section-item{ border-top: 1px dotted #EEE;position: relative;padding: 12px 0;}
    .item-img-avatar{ float: left;height: 50px;margin: 2px 10px 0 0;width: 50px;border: 0 none;border-radius: 2px;}
    .list-content-title{font-weight: 700;margin: 0;line-height: 20px;font-size: 14px;outline: 0;}
    .big-gray{  color: #999;font-size: 14px;font-weight: 400;}
    .details{color: #999;font-size: 12px;font-weight: 400;}
    .details .link-gray-normal{color: #999;font-weight: 400;}
    .more .dropdown-menu{min-width: 80px;width: 92px;}
</style>
<div class="list-view" style='<?php echo $this->getAction()->getId() == "query" ? "padding-bottom: 70px;" : "padding-bottom: 30px;"; ?>'>
    <?php $userId = ($type == "followees") ? ($followees == "" ? array() : explode(",", trim($followees, ","))) : ($followers == "" ? array() : explode(",", trim($followers, ","))); ?>
    <?php
    if (count($userId) == 0) {
        echo "<div class='alert alert-info'>没有找到相关的用户.</div>";
    } else {
        for ($j = 0; $j < count($userId); $j++) {
            $userModel = User::model()->findByPk($userId[$j]);
            ?>
            <div class="profile-section-item clearfix">
                <div style="float: right;">
                    <?php if (in_array(Yii::app()->user->id, explode(",", $userModel->followees)) && !Yii::app()->user->isGuest) { ?>
                        <div style="display: inline-block;color:#999;padding-right:4px;"><?php echo $userModel->gender == 1 ? "他" : "她"; ?>也关注了你</div>
                    <?php } ?>
                    <button  class="btn btn-small <?php echo (!in_array(Yii::app()->user->id, explode(",", $userModel->followees)) || Yii::app()->user->isGuest) ? "btn-success" : "btn-success"; ?>"  name="<?php echo Yii::app()->user->isGuest ? 'noLogin' : 'attention'; ?>" data-uid="<?php echo $userModel->id; ?>" style="width: 78px;"><?php echo (!in_array($userModel->id, explode(",", User::model()->findByPk(Yii::app()->user->id)->followees)) || Yii::app()->user->isGuest) ? "立即关注" : "取消关注"; ?></button>
                    <?php
                    Yii::app()->controller->widget('bootstrap.widgets.TbButtonGroup', array(
                        'size' => 'small',
                        'htmlOptions' => array('class' => 'more', 'title' => '更多'),
                        'buttons' => array(
                            array('label' => '更多操作', 'url' => 'javascript:;'),
                            array(
                                'items' => array(
                                    array('label' => '与' . User::getSex($userModel->gender) . '对话', 'url' => 'javascript:;', 'linkOptions' => array('class' => 'td-link-icon', 'name' => Yii::app()->user->isGuest ? 'noLogin' : 'reply', 'data-uid' => $userModel->id, 'data-username' => $userModel->user_name)),
                                    array('label' => '举报用户', 'url' => 'javascript:;', 'linkOptions' => array('class' => 'td-link-icon', 'name' => Yii::app()->user->isGuest ? 'noLogin' : 'report', 'data-uid' => $userModel->id, 'data-username' => $userModel->user_name)),
                                    array('label' => (!in_array($userModel->id, explode(",", User::model()->findByPk(Yii::app()->user->id)->block_users)) || Yii::app()->user->isGuest) ? '屏蔽用户' : '取消屏蔽', 'url' => 'javascript:;', 'linkOptions' => array('class' => 'td-link-icon', 'name' => Yii::app()->user->isGuest ? 'noLogin' : 'block', 'data-uid' => $userModel->id, 'block-value' => ((Yii::app()->user->isGuest) || (!in_array($userModel->id, explode(",", User::model()->findByPk(Yii::app()->user->id)->block_users)))) ? "0" : "1"))),
                    ))));
                    ?>
                </div>
                <a title="<?php echo $userModel->user_name; ?>"  href="<?php echo $this->createUrl("/default/userinfo", array("user_id" => $userModel->id)); ?>">
                    <img src="<?php echo $this->createUrl("/default/getimage", array("id" => $userModel->id, "type" => "avatar")) ?>" class="item-img-avatar">
                </a>
                <div class="zm-list-content-medium">
                    <h2 class="list-content-title"><a  href="<?php echo $this->createUrl("/default/userinfo", array("user_id" => $userModel->id)); ?>" class="zg-link" title="<?php echo $userModel->user_name; ?>"><?php echo $userModel->user_name; ?></a></h2>
                    <div class="big-gray">
                        <?php
                        if ($userModel->tags != "") {
                            $tagsArray = explode(",", $userModel->tags);
                            for ($i = 0; $i < count($tagsArray); $i++) {
                                ?>
                                <a title="<?php echo $tagsArray[$i]; ?>"  href="<?php echo $this->createUrl("/default/query", array("q" => $tagsArray[$i], "type" => "user")); ?>" class="label <?php echo (isset($_GET["q"]) && ($_GET["q"] == $tagsArray[$i])) ? 'label-info' : ''; ?>"><?php echo $tagsArray[$i]; ?></a> 
                                <?php
                            }
                        }
                        ?>
                    </div>
                    <div class="details">
                        <a   href="<?php echo $this->createUrl("/default/userinfo", array("user_id" => $userModel->id, "type" => "followees")); ?>" class="link-gray-normal">关注了 <?php echo $userModel->followees == '' ? 0 : count(explode(",", trim($userModel->followees, ","))); ?></a>
                        /
                        <a  href="<?php echo $this->createUrl("/default/userinfo", array("user_id" => $userModel->id, "type" => "followers")); ?>" class="link-gray-normal">关注者 <?php echo $userModel->followers == '' ? 0 : count(explode(",", trim($userModel->followers, ","))); ?></a>
                        /
                        <a href="<?php echo $this->createUrl("/default/userinfo", array("user_id" => $userModel->id, "type" => "article")); ?>" class="link-gray-normal">文章 <?php echo Article::model()->count("create_user=:create_user", array(":create_user" => $userModel->id)); ?></a>
                        /
                        <a href="<?php echo $this->createUrl("/default/userinfo", array("user_id" => $userModel->id, "type" => "question")); ?>" class="link-gray-normal">问题 <?php echo Question::model()->count("create_user=:create_user", array(":create_user" => $userModel->id)); ?></a>
                        /
                        <a href="<?php echo $this->createUrl("/default/userinfo", array("user_id" => $userModel->id, "type" => "wealth")); ?>" class="link-gray-normal">财富值 <?php echo $userModel->wealth; ?></a>
                    </div>
                </div>
            </div>
        <?php
        }
    }
    ?>
</div>
