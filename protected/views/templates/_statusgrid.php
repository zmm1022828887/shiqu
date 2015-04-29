<ul class="status-list"> 
    <li><span>问题总数：<?php echo Question::model()->count(); ?></span><span>文章总数：<?php echo Article::model()->count("publish=1"); ?></span></li>
    <li><span>评论总数：<?php echo Comment::model()->count("parent_id=0") + SysComment::model()->count(); ?></span><span>会员总数：<?php echo User::model()->count(); ?></span></li>
    <li><span>回答总数：<?php echo Answer::model()->count(); ?></span><span>访问总数：<?php echo Sys::model()->find()->view_count; ?></span></li>
</ul>