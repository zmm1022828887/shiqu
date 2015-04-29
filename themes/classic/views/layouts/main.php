<?php
$baseUrl=Yii::app()->theme->baseUrl;
?>
<!DOCTYPE html>
<html lang="en">
    <head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />
        <!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame -->
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <title><?php echo CHtml::encode($this->pageTitle); ?></title>
        <link rel="shortcut icon" href="<?php echo $baseUrl; ?>/public/ico/favicon.ico">
        <link href="<?php echo $baseUrl; ?>/public/css/base.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="<?php echo $baseUrl ?>/public/js/jquery.form.js"></script>
        <link href="<?php echo $baseUrl; ?>/public/css/jquery.autocomplete.css" rel="stylesheet" type="text/css" />
        <script src="<?php echo $baseUrl; ?>/public/js/jquery.autocomplete.js" type="text/javascript"></script> 
         <script src="<?php echo $baseUrl; ?>/public/js/jquery.nicescroll.js" type="text/javascript"></script> 
    </head>
    <body>
        <div id="container-scroller">
            <div>
                <?php $this->renderPartial('../_header');?>
                <?php echo $content; ?>
                <?php $this->renderPartial('../_footer'); ?>
            </div>
        </div>
    </body>
</html>