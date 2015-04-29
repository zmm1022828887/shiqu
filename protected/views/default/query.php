<style>
    .content{margin: 0 auto; width: 1030px; position: relative;padding-top: 30px;}
    .content legend{border-bottom:none;}
    .content .control-label{text-align: left;}
    .hot-category-list{border-bottom: 1px dotted #ccc;padding-top: 10px;padding-bottom: 10px}
    .hot-category-list .l-title{float: left;width: 60px;text-align: right;}
    .hot-category-list .r-list a{color:#fff;}
    .content .right-tab .list-view{padding-top: 0;}
</style>   
<?php $this->pageTitle = "搜索小组、话题、共享日志或人 - " . Yii::app()->name; ?>
<div class="content">
    <div class="tabbable">
        <ul class="nav nav-tabs">
            <li class="<?php echo (!isset($_GET['type']) || $_GET['type'] == 'topic') ? "active" : '' ?>">
                <a href="<?php echo $this->createUrl('query', array('q' => $_GET["q"], 'type' => 'topic')); ?>">话题</a>
            </li>
            <li class="<?php echo $_GET['type'] == 'article' ? "active" : '' ?>">
                <a href="<?php echo $this->createUrl('query', array('q' => $_GET["q"], 'type' => 'article')); ?>">文章</a>
            </li>
            <li class="<?php echo $_GET['type'] == 'question' ? "active" : '' ?>">
                <a href="<?php echo $this->createUrl('query', array('q' => $_GET["q"], 'type' => 'question')); ?>">问题</a>
            </li>
            <li class="<?php echo $_GET['type'] == 'user' ? "active" : '' ?>">
                <a href="<?php echo $this->createUrl('query', array('q' => $_GET["q"], 'type' => 'user')); ?>">用户</a>
            </li>
        </ul>
        <div class="right-tab tab-content clearfix" style="<?php echo (($_GET['type'] == "user") && (count($dataProvider->getdata())) != 0) ? "padding:0 12px 20px 0" : ""; ?>">
            <div class="tab-pane active vote-tabs" >
                <?php
                if (!isset($_GET['type']) || ($_GET['type'] == "topic"))
                    if (count($topicDataProvider->getdata()) == 0) {
                        echo "<div class='alert alert-info'>没有找到相关的话题．</div>";
                    } else {
                        $this->renderPartial('../_jointopiclist');
                    }
                if ($_GET['type'] == "user")
                    $this->renderPartial('../_followeeslist', array("type" => "followers", 'followers' => $followers));
                if ($_GET['type'] == "question") {
                    if (count(Question::model()->search("new")->getdata()) == 0) {
                        echo "<div class='alert alert-info'>没有找到相关的问题．</div>";
                    } else {
                        $this->renderPartial('../_questiontabs', array("type" => "new"));
                    }
                }
                if ($_GET['type'] == "article") {
                    if (count(Article::model()->search()->getdata()) == 0) {
                        echo "<div class='alert alert-info'>没有找到相关的文章．</div>";
                    } else {
                        $this->renderPartial('../_articletabs', array("type" => "new"));
                    }
                }
                ?>
                <?php
                ?>
            </div>
        </div>
    </div>
</div>