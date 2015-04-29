<?php
if ($type != "hot") {
    $this->widget('bootstrap.widgets.TbGroupGridView', array(
        'id' => $type.'-article-grid',
        'dataProvider' => Article::model()->search($type),
        'extraRowColumns' => array('create_time'),
        'template' => '{items}',
        'ajaxUpdate' => true,
        'hideHeader' => true,
        'emptyText'=>'<div class="alert alert-info">暂无文章</div>',
        'extraRowExpression' => 'User::dateToText($data->create_time,"long")',
        'columns' => array(
            array(
                'name' => 'create_time',
                'value' => 'User::dateToText($data->create_time,"short")',
                'headerHtmlOptions' => array('style' => 'display:none;'),
                'htmlOptions' => array('style' => 'display:none;'),
            ),
            array(
                'name' => 'title',
                'value' => array($this, 'renderArticleDiv'),
                'type' => 'raw',
                'headerHtmlOptions' => array('style' => 'width: 100%'),
                'htmlOptions' => array('class' => 'ellipsis list-product'),
            ),
        ),
    ));
} else {
    $this->widget('bootstrap.widgets.TbGroupGridView', array(
        'id' => 'article-grid',
        'dataProvider' => Vote::model()->search("article"),
        'template' => '{items}',
        'ajaxUpdate' => true,
        'emptyText'=>'<div class="alert alert-info">暂无文章</div>',
        'hideHeader' => true,
        'columns' => array(
            array(
                'name' => 'title',
                'value' => array($this, 'renderArticleVoteDiv'),
                'type' => 'raw',
                'headerHtmlOptions' => array('style' => 'width: 100%'),
                'htmlOptions' => array('class' => 'ellipsis list-product'),
            ),
        ),
    ));
}
?>