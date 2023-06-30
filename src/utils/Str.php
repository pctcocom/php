<?php
namespace pctco\php\utils;
class Str{
    public function random(int $length,int $types) {
        $arr = [
            1 => "0123456789",
            2 => "abcdefghijklmnopqrstuvwxyz",
            3 => "ABCDEFGHIJKLMNOPQRSTUVWXYZ",
            4 => "~@#$%^&*(){}[]|"
        ];
        
        if($types === 0) {
            array_pop($arr);
            $string = implode("",$arr);
        }else if($types === -1) {
            dump($types);
            $string = implode("",$arr);
        }else{
            $string = $arr[$types];
        }
        $count = strlen($string) - 1;
        $result = '';
        for($i = 0; $i < $length; $i++){
            $str[$i] = $string[rand(0, $count)];
            $result .= $str[$i];
        }
        return $result;
    }

    public static function only(int $type = 0,int $length = 18,int $time=0){
		$str = $time == 0 ? '':date('YmdHis',time());
	    switch ($type) {
	        case 0:
	            for((int)$i = 0;$i <= $length;$i++){
	                if(mb_strlen($str) == $length){
	                    $str = $str;
	                }else{
	                    $str .= rand(0,9);
	                }
	            }
	            break;
	        case 1:
	            for((int)$i = 0;$i <= $length;$i++){
	                if(mb_strlen($str) == $length){
	                    $str = $str;
	                }else{
	                    $rand = "qwertyuioplkjhgfdsazxcvbnm";
	                    $str .= $rand[mt_rand(0,strlen($rand) - 1)];
	                }
	            }
	            break;
	        case 2:
	            for((int)$i = 0;$i <= $length;$i++){
	                if(mb_strlen($str) == $length){
	                    $str = $str;
	                }else{
	                    $rand = "QWERTYUIOPLKJHGFDSAZXCVBNM";
	                    $str .= $rand[mt_rand(0,strlen($rand) - 1)];
	                }
	            }
	            break;
	        case 3:
	            for((int)$i = 0;$i <= $length;$i++){
	                if(mb_strlen($str) == $length){
	                    $str = $str;
	                }else{
	                    $rand = "123456789qwertyuioplkjhgfdsazxcvbnmQWERTYUIOPLKJHGFDSAZXCVBNM";
	                    $str .= $rand[mt_rand(0,strlen($rand) - 1)];
	                }
	            }
	            break;
	        case 4:
	            for((int)$i = 0;$i <= $length;$i++){
	                if(mb_strlen($str) == $length){
	                    $str = $str;
	                }else{
	                    $rand = "!@#$%^&*()_+=-~`";
	                    $str .= $rand[mt_rand(0,strlen($rand) - 1)];
	                }
	            }
	            break;
	        case 5:
	            for((int)$i = 0;$i <= $length;$i++){
	                if(mb_strlen($str) == $length){
	                    $str = $str;
	                }else{
	                    $rand = "123456789qwertyuioplkjhgfdsazxcvbnmQWERTYUIOPLKJHGFDSAZXCVBNM!@#$%^&*()_+=-~`";
	                    $str .= $rand[mt_rand(0,strlen($rand) - 1)];
	                }
	            }
	            break;
	    }
	    return $str;
	}
}