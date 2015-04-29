<style>
    .content{margin: 0 auto; width: 1030px; }
    .main-content{padding: 25px 0 50px 0;}
    .content .content-left{width: 690px; float: left;}
    /*右侧样式*/
    .content .content-right{width: 260px; float: right;}
    .content .content-right .content-sidebar h5{text-align: left;padding: 0; margin: 0;}
</style>
<?php
 $this->pageTitle = "私信 - " .Yii::app()->name;
 ?>
<div class="content clearfix">
    <div class="main-content">
        <div class="content-left">
            <fieldset>
                <legend style="margin-bottom:10px;font-size:15px;">我和<b> <?php echo  User::getNameById($_GET["id"]);?> </b> 的私信对话 共 <b class="total"><?php echo $total;?></b> 条<a class="btn  btn-info btn-mini <?php echo Yii::app()->user->isGuest ? 'noLogin' : 'CreteMessage'; ?>" href="<?php echo isset($_GET["type"]) ? $this->createUrl('/default/notify',array("type"=>"message")) : $this->createUrl('/default/inbox'); ?>" style="margin-top:10px;margin-right:5px;float:right;" href="javascript::">返回列表</a></legend>
            </fieldset>
            <div class="list-content">
                <?php echo $this->renderPartial('../_dialogue', array('model' => $data), true, false); ?>
            </div>
        </div>
        <div class="content-right alert alert-info">担心骚扰？可以 设置 为「只允许我关注的人给我发私信」。</div>
    </div>
</div>