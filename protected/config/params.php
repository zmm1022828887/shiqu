<?php
return array(
    'imagePath'=>dirname(__FILE__) . "/../../productimage/",
    'tmp'=>dirname(__FILE__) . "/../../tmp/",
    'avatarPath'=>dirname(__FILE__) . "/../../avatar/",
    'photoPath'=>dirname(__FILE__) . "/../../photo/",
    'timelinePath'=>dirname(__FILE__) . "/../../timeline/",
    'groupPath'=>dirname(__FILE__) . "/../../group/",
     //上传设置
    'upload' => array(
        //限制上传类型，为空不限制
        'limit_type' => array("gif","jpg","png","tiff"),
    ),
    'siteInfo'=>array(
        'mail'=>'zmm1022828887@126.com',//发送方邮箱
        'password'=>'Bw0EAx8HDC1G',//发送方邮箱密码
        'site_name'=>'入手网',//站点名称
       'domain_name'=>'09长理计算机',//站点域名
    ),
);
?>
