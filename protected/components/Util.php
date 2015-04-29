<?php
class Util{
    /**
     * 字符串加密、解密函数
     *
     *
     * @param   string  $txt        字符串
     * @param   string  $operation  ENCODE为加密，DECODE为解密，可选参数，默认为ENCODE，
     * @param   string  $key        密钥：数字、字母、下划线
     * @return  string
     */
    public static function sys_auth ($txt, $operation = 'ENCODE', $key = '') {
        $key = $key ? $key : "key1988";
        $txt = $operation == 'ENCODE' ? ( string ) $txt : base64_decode($txt);
        $len = strlen($key);
        $code = '';
        for ($i = 0; $i < strlen($txt); $i ++) {
            $k = $i % $len;
            $code .= $txt [$i] ^ $key [$k];
        }
        $code = $operation == 'DECODE' ? $code : base64_encode($code);
        return $code;
    }
    public static function emailSmtp($mail){
        $mail=  explode('@', $mail);
        $smtp='smtp.'.end($mail);
        return $smtp;
    }
    public static function deldir($dir) {
        $dh=opendir($dir);
        while ($file=readdir($dh)) {
            if($file!='.' && $file!='..'){
                $fullpath=$dir.'/'.$file;
                if(!is_dir($fullpath)) {
                    unlink($fullpath);
                } else {
                    self::deldir($fullpath);
                }
            }
        }
        closedir($dh);
        if(rmdir($dir)) {
            return true;
        } else {
            return false;
        }
    }
   }
?>
