<?php
$this->pageTitle = $message
?>
<style>
    .tip-modal{
        width: 560px;
        background-color: #ffffff;
        border: 1px solid #999;
        border: 1px solid rgba(0, 0, 0, 0.3);
        margin:  20px auto;
        border-radius:6px;
    }
</style>
<div id="myModal" class="tip-modal" data-backdrop="static">  
    <div class="modal-header">  
        <h3><i class="icon-warning-2" style="margin-right: 10px;"></i>温馨提示</h3>  
    </div>  
    <div class="modal-body">  
        <p class="alert alert-info"><?php echo $message; ?></p>  
    </div>  
    <div class="modal-footer">  
        <a href="#" class="btn" onclick="window.close();">关闭</a>  
        <a  class="btn btn-primary" href="<?php echo $this->createUrl('default/index'); ?>">返回首页</a>  
    </div>  
</div>  