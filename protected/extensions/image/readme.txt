璇存槑锛?
绉绘鑷狵ohana鐨処mage绫诲簱

鑻辨枃鏂囨。鍦板潃锛歨ttp://docs.kohanaphp.com/libraries/image
涓枃鏂囨。鍦板潃锛歨ttp://khnfans.cn/docs/libraries/image

------------------------------------------------------------------------------

瀹夎锛?
灏唅mage鏂囦欢澶规斁鍏pplication鐨別xtensions鏂囦欢涓嵆鍙?

------------------------------------------------------------------------------

浣跨敤鏂规硶锛?

绗竴绉嶏細
閰嶇疆锛?
鍦╝pplication鐨刴ain config鐨刢omponents涓坊鍔犱互涓嬮厤缃?
'image'=>array(
            'class'=>'application.extensions.image.CImageComponent',
            // GD or ImageMagick
            'driver'=>'GD',
            // ImageMagick setup path
            'params'=>array('directory'=>'D:/Program Files/ImageMagick-6.4.8-Q16'),
        ),

璋冪敤鏂规硶()锛?
$image = Yii::app()->image->load('images/test.jpg');
$image->resize(400, 100)->rotate(-45)->quality(75)->sharpen(20);
$image->save(); // or $image->save('images/small.jpg');

绗簩绉嶏細
Yii::import('application.extensions.image.Image');
$image = new Image('images/test.jpg');
$image->resize(400, 100)->rotate(-45)->quality(75)->sharpen(20);
$image->render();