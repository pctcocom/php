<?php
namespace pctco\php\safety\algorithm\num;
use pctco\php\Helper;
use pctco\php\safety\algorithm\num\Skip32;
class Tools{
    /** 
     ** 目前 Skip 最大值是加密到  4294967295  （已解决现在可以无限加密）
     */
    private static $MaxNumber = 4294967295;
    /** 
     ** 目前最大值  4294967295 的填充
     */
    private static $MaxNumberLength = '0000000000';
    public function __construct($config){
        $this->config = $config;
        $this->token = false;
        $this->data = 0;
	}
    public function result(bool $event,$model = 'user',$data){
        foreach ($this->config->model as $k => $v) {
            if ($k === $model) {
                $this->token = $v;
                break;
            }
        }
        $this->data = $data;
        return $event === true?$this->encrypt():$this->decrypt();
    }
    public function encrypt(){
        // 溢出（无法在进行加密计算时启动）：当遇到超大值时选择填补机制
        $multiple = (int)$this->data/self::$MaxNumber;
        if ($multiple > 1) {
           // 获取倍数
           $multiple = (int)ceil($multiple);
  
           // 假设想要加密的数字是：4294967295*20 + 10;  $e = e20;
           $e = '00000'.(string)($multiple - 1).'00000';
  
           // 溢出：10
           $overflow = self::$MaxNumber - abs(self::$MaxNumber*$multiple - $this->data);
           // 加密溢出
           $env = Skip32::encrypt($this->token,$overflow);
           
           // 填充0：如果加密后不到10个数字,则填充到10个数字
           $env = substr_replace(self::$MaxNumberLength,$env,-strlen($env));

           return $e.$env;
        }else{
           return Skip32::encrypt($this->token,$this->data);
        }
    }
    public function decrypt(){
        // 溢出超过值 4294967295 处理
        if (strlen($this->data) > 10) {
           // EN Number
           $encrypt = substr($this->data,-10);
           // 溢出的倍数
           $overflow = substr($this->data,5,-15);
           return $overflow*self::$MaxNumber + Skip32::decrypt($this->token,$encrypt);
        }else{
           return (int)Skip32::decrypt($this->token,$this->data);
        }
    }
}
