<?php
namespace pctco\php\files\file\cloud\storage;
class Processor{
   private $client;
   /**
   * @access 配置
   * @return
   **/
   function __construct($config){
      switch ($config->app) {
         case 'aliyunOss':
            $this->client = new Aliyun($config->objectStorage->aliyunOss);
            break;
         case 'baiDuCloudBos':
            $this->client = new BaiDu($config->objectStorage->baiDuCloudBos);
            break;
         case 'tencentCloudCos':
            $this->client = new Tencent($config->objectStorage->tencentCloudCos);
            break;

         default:
            // code...
            break;
      }
   }
   /**
   * @name 字符串上传
   * @describe put
   * @param mixed $file 文本路径
   * @param mixed $content 字符串内容
   * @return boolean
   **/
   public function put($file,$content){
      return $this->client->put($file,$content);
   }
   /**
   * @name 字符串读取
   * @describe get
   * @param mixed $file 文本路径
   * @return boolean
   **/
   public function get($file){
      return $this->client->get($file);
   }
   /**
   * @name 文件拷贝
   * @describe copy
   * @param mixed $FromFile 文本路径(从)
   * @param mixed $ToFile 文本路径(到)
   * @return boolean
   **/
   public function copy($FromFile,$ToFile){
      return $this->client->copy($FromFile,$ToFile);
   }
   /**
   * @name 列举文件
   * @describe list
   * @param mixed $dir 文件夹路径 dir/
   * @return boolean
   **/
   public function list($dir){
      return $this->client->list($dir);
   }
   /**
   * @name upload
   * @describe 上传文件
   * @param string or array $file
   * @return boolean
   **/
   public function upload($file){
      return $this->client->upload($file);
   }
   /**
   * @name delete
   * @describe 删除文件
   * @param string or array $file
   * @return boolean
   **/
   public function delete($file){
      return $this->client->delete($file);
   }
   /**
   * @name exist
   * @describe 判断文件是否存在
   * @param string $file
   * @return boolean
   **/
   public function exist($file){
      return $this->client->exist($file);
   }
}
