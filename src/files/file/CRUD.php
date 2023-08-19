<?php
namespace pctco\php\files\file;
use pctco\php\Helper;
class CRUD{
   /** 
    ** 打开需要处理的文件
    *? @date 23/07/11 09:44
    *  @param Number $id
    *  @param String $file
    *  @param String $suffix true = 自动识别后缀
    *  @param String $model 如：cover、avatar、news
    *  @param String $alias 别名 如：cover、avatar、news
    *! @return 
    */
   public function open(array $options = []){
      $this->pctco = Helper::pctco([
         'utils' => ['Arr','Str'],
         'files',
         'safety' => ['verify']
      ]);
      $options = 
      $this->pctco->utils->Arr->merge([],[
         'id' => 0,
         'file' => false,
         'suffix' => '.webp',
         'model'   => 'cover',
         'alias'  => 'cover',
         'names'  => true
      ],$options);

      $this->options = $this->pctco->utils->Arr->obj($options);

      $this->config = Helper::config('get::file::cloud::storage');

      $this->entranceDir = $this->pctco->app->path->entrance;

      if ($this->options->id === 0) return $this;

      if ($this->config->use === 1) $this->storage = new cloud\storage\Processor($this->config);

      $id = abs(intval($this->options->id));
      $id = sprintf("%09d", $id);
      $dir1 = substr($id, 0, 3);
      $dir2 = substr($id, 3, 2);
      $dir3 = substr($id, 5, 2);

      $this->dirs = $this->options->model;
      
      $this->suffix = $this->options->suffix === true?strrchr($this->options->file,'.'):$this->options->suffix;

      if ($this->options->names === true) {
         $this->fileName = md5($this->options->id).'-'.$this->options->alias.$this->suffix;
      }else{
         $this->fileName = md5($this->pctco->utils->Str->random(32,4)).'-'.$this->options->alias.$this->suffix;
      }

      $this->path = 'uploads/'.$this->options->model.'/'.$dir1.'/'.$dir2.'/'.$dir3.'/';
      $this->dir = $this->entranceDir.'/'.$this->path;

      if ($this->options->file !== false) {
         $picture = new Picture;
         if ($this->pctco->safety->verify->open($this->options->file)->rule('format.link.img')->check() === false) {
            $image = $picture->open($this->options->file)->base64ToImage([
               'savePath' => 'uploads/temp/',
               'date' => ['y','m'],
               'fileName'   => true,
               'enableNotCloudStorage' =>  false
            ]);
         }else{
            $image = 
            $picture->open($this->options->file)->save([
               'path'   => 'uploads/temp/',
               'date'   => ['y','m'],
               'enableNotCloudStorage' => false
            ]);
         }

         if ($image['code'] === 200) {
            $this->file = $image['data']['file']['system'];
         }else{
            $this->file = $this->options->file;
         }
      }
      
      return $this;
   }
   /** 
    ** 获取文件路径
    *? @date 23/07/10 17:19
    */
   public function get(){
      if ($this->config->use === 1) {
         if($this->storage->exist($this->path.$this->fileName)) {
            return $this->config->domain.'/'.$this->path.$this->fileName;
         } else {
            return '/uploads/'.$this->dirs.'/'.$this->options->alias.$this->suffix;
         }
      }else{
         if($this->pctco->files->utils('file')->utils->open($this->dir.$this->fileName,[])->isFile()) {
            return '/'.$this->path.$this->fileName;
         } else {
            return '/uploads'.'/'.$this->dirs.'/'.$this->options->alias.$this->suffix;
         }
      }
   }
   /** 
    ** 保存文件
    *? @date 23/07/10 17:20
    */
   public function save(){
      $filesUtils = 
      $this->pctco
      ->files->utils('file')->utils;
      
      if ($filesUtils->open($this->dir,[])->exists() === false) $filesUtils->mkdirs();
      try {
         $picture = new Picture;
         $picture->open($this->file)->save([
            'savePath' => $this->path,
            'date' => false,
            'fileName'   => $this->fileName,
            'curl'  =>  false,
            'enableNotCloudStorage' =>  false
         ]);
      } catch (\Exception $e) {
         return $this->get();
      }
  
      if ($this->config->use === 1) {
         $upload = $this->storage->upload($this->path.$this->fileName);
         $filesUtils->open($this->dir.$this->fileName,[])->delete();
      }
  
      $filesUtils->open($this->file,[])->delete();
      return $this->get();
   }
   /** 
    ** 删除文件
    *? @date 23/07/10 17:21
    */
   public function delete(){
      if ($this->config->use === 1) {
         return $this->storage->delete($this->path.$this->fileName);
      }
      return $this->pctco->files->utils('file')->utils->open($this->dir.$this->fileName,[])->delete();
   }
   /** 
    ** 替换附件链接路径 {os}
    *? @date 23/07/11 14:11
    *! @return String
    */
   public function path(){
      if ($this->config->use === 0) $this->config->domain = '';

      $var = strpos($this->options->file, $this->config->var);
      if ($var === false) {// 将存储域名替换成var
         return str_replace($this->config->domain,$this->config->var,$this->options->file);
      }else{// 将var替换成存储域名
         return str_replace($this->config->var,$this->config->domain,$this->options->file);
      }
   }
}