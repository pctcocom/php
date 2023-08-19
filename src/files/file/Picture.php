<?php
namespace pctco\php\files\file;
use pctco\php\Helper;
class Picture{
    /** 
     ** 打开文件
     *? @date 23/07/11 10:23
     *  @param String $file		需要保存图片的链接，可以是本地文件、远程链接文件
     */
    public function open(string $file){
        $this->file = trim($file);
        $this->config = Helper::config('get::file::cloud::storage');
        $this->pctco = Helper::pctco([
            'utils' =>  ['Arr','Request'],
            'safety'    =>  ['verify']
        ]);
        $this->entranceDir = $this->pctco->app->path->entrance;
        return $this;
    }
    /** 
     ** 保存图片
     *? @date 23/07/11 09:44
     *  @param String $savePath		保存路径  如 uploads/temp/
     *  @param Array $date    保存路径日期 ['y','m','d'] 或 false 则不使用日期
     *  @param Boolean $fileName	保存的文件名称(默认md5)  true = 自动生成文件名 , 字符串 'test-name'
     *  @param Boolean $curl		获取远程文件所采用的方法
     *  @param Boolean $enableNotCloudStorage    是否强制开启或关闭对象存储桶模式
     *! @return Array
     */
    public function save(array $options = []){
        $options = 
        $this->pctco->utils->Arr->merge([],[
            'savePath' => 'uploads/temp/',
            'date' => ['y','m','d'],
            'fileName'   => true,
            'curl'  =>  false,
            'enableNotCloudStorage' =>  true
        ],$options);

        $options = $this->pctco->utils->Arr->obj($options);

        if(empty($this->file)){
            return [
                'status'    =>  'error',
                'code'  =>  101,
                'tips'   => 'error',
                'message'   => 'Link does not exist',
                'system_message'    =>  'Link does not exist'
            ];
        }

        try {
            $ext = false;
            $getimagesize = getimagesize($this->file);
            $image = preg_replace('/image\//','.',$getimagesize['mime'],1);
            if (!empty($image)) {
                if ($image === $getimagesize['mime']) {
                    return [
                        'status'    =>  'error',
                        'code'  =>  102,
                        'tips'   => 'error',
                        'message'   => 'Link has expired',
                        'system_message'    =>  'Link has expired'
                    ];
                }else{
                    $ext = $image;
                }
            }else{
                /** 
                 ** 处理.svg
                *? @date 22/08/02 03:13
                */
                $image = parse_url($this->file);
                if (empty($image['path'])) {
                    $ext = false;
                }else{
                $ext = strrchr($image['path'],'.');
                    if ($ext !== '.svg') $ext = false;
                }
                
            }
        } catch (\Exception $e) {
            return [
                'status'    =>  'error',
                'code'  =>  103,
                'tips'   => 'error',
                'message'   => 'Link has expired',
                'system_message'    =>  'Link has expired'
            ];
        }

        if(empty($options->savePath)){
            return [
                'status'    =>  'error',
                'code'  =>  104,
                'tips'   => 'error',
                'message'   => 'Path does not exist',
                'system_message'    =>  'Path does not exist'
            ];
        }

        /** 
         ** 创建文件名
         *? @date 23/07/11 11:18
         */
        if($options->fileName === true){
            if ($ext !== false) {
                $fileName = md5(time().rand(1,99999999)).$ext;
            }else{
                return [
                    'status'    =>  'error',
                    'code'  =>  105,
                    'tips'   => 'error',
                    'message'   => 'Picture suffix is not supported',
                    'system_message'    =>  'Picture suffix is not supported'
                ];
            }
        }else{
            $fileName = $options->fileName;
        }

        /** 
         ** 创建保存目录
         *? @date 23/07/11 11:18
         */
        $saveDate = '';
        if (is_array($options->date)) {
            foreach ($options->date as $v) {
                $saveDate .= date($v).'/';
            }
        }
        
        $savePath = $this->entranceDir.'/'.$options->savePath.$saveDate;
        if(!file_exists($savePath) && !mkdir($savePath,0777,true)){
            return [
                'status'    =>  'error',
                'code'  =>  106,
                'tips'   => 'error',
                'message'   => 'Create a save directory',
                'system_message'    =>  'Create a save directory'
            ];
        }

        if($options->curl){
            // 普通
            $ch = curl_init();
            $timeout = 5;
            curl_setopt($ch,CURLOPT_URL,$this->file);
            curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);

            $img = curl_exec($ch);
            curl_close($ch);
        }else{
            ob_start();
            @readfile($this->file);
            $img = ob_get_contents();
            ob_end_clean();
        }

        $fp2 = @fopen($savePath.$fileName,'a');
        fwrite($fp2,$img);
        fclose($fp2);
        unset($img,$this->file);

        $absolute = '/'.$options->savePath.$saveDate.$fileName;
        if ($options->enableNotCloudStorage === true) {
            if ($this->config->use === 1) {
                $storage = new cloud\storage\Processor();
                $upload = $storage->upload($path.$saveDate.$fileName);
                if ($upload === true) {
                    $absolute = $this->config->domain.$absolute;
                    $file = new File($savePath.$FileName);
                    $file->delete();
                }
            }
        }

        return [
            'status'    =>  'success',
            'code'  =>  200,
            'tips'   => 'success',
            'message'   => 'Data request success',
            'system_message'    =>  'Data request success',
            'data'  =>  [
                'date'=>$saveDate,
                'name'=>$fileName,
                'file'=>[
                    'relative'   =>   $saveDate.$fileName,
                    'system'   =>   $savePath.$fileName,
                    'absolute'   =>   $absolute,
                ]
            ]
        ];
    }
    public function base64(){
        if ($this->pctco->safety->verify->open($this->file)->rule('html.href.link')->check()) {
            return $this->imageToBase64();
        }else{
            return $this->base64ToImage($vars);
        }
    }
    /** 
     ** 保存base64数据为图片
     *? @date 23/07/11 13:11
     *  @param $base64  base64编码
     *  @param $savePath    保存路径  entrance/uploads/temp/
     *  @param $date    保存路径日期
     *  @param $FileName    自动生产文件名
     *  @param $enableNotCloudStorage    是否强制开启或关闭 os
     *! @return 
     */
	public function base64ToImage(array $options = []){
        $options = 
        $this->pctco->utils->Arr->merge([],[
            'savePath' => 'uploads/temp/',
            'date' => ['y','m','d'],
            'fileName'   => true,
            'enableNotCloudStorage' =>  true
        ],$options);
        

        $options = $this->pctco->utils->Arr->obj($options);
        
		//匹配出图片的格式
        $result = $this->pctco->safety->verify->open($this->file)->rule('format.img.base64')->find();

        if (!empty($result[2][0])){
            // 格式 png
            $ext = $result[2][0];
            if ($ext === 'svg+xml') $ext = 'svg';
            $abc = '88888';
            if ($options->fileName === true) {
                $fileName = md5(time().rand(1,999999999)).'.'.$ext;
            }else{
                $fileName = $options->fileName.'.'.$ext;
            }

            $saveDate = '';
            foreach ($options->date as $v) {
                $saveDate .= date($v).'/';
            }

            $savePath = $this->entranceDir.'/'.$options->savePath.$saveDate;

            //创建保存目录
            if(!file_exists($savePath) && !mkdir($savePath,0777,true)){
                return [
                    'status'    =>  'error',
                    'code'  =>  101,
                    'tips'   => 'error',
                    'message'   => 'Create a save directory',
                    'system_message'    =>  'Create a save directory'
                ];
            }

            $savePath = $savePath.$fileName;
            if (file_put_contents($savePath,base64_decode(str_replace($result[1], '', $this->file)))){

                $absolute = '/'.$options->savePath.$saveDate.$fileName;

                if ($options->enableNotCloudStorage) {
                    if ($this->config->use === 1) {
                        $storage = new cloud\storage\Processor($this->config);
                        $upload = $storage->upload($options->savePath.$saveDate.$fileName);
                        if ($upload === true) {
                            $absolute = $this->config->domain.$absolute;
                            $this->pctco->files->utils('file')->utils->open($savePath,[])->delete();
                        }
                    }
                }

                return [
                    'status'    =>  'success',
                    'code'  =>  200,
                    'tips'   => 'success',
                    'message'   => 'Data request success',
                    'system_message'    =>  'Data request success',
                    'data'  =>  [
                        'date'  =>  $saveDate,
                        'name'  =>  $fileName,
                        'file'  =>  [
                            'relative'   =>   $saveDate.$fileName,
                            'system'   =>   $savePath,
                            'absolute'   =>   $absolute,
                        ]
                    ]
                ];
            }else{
                return [
                    'status'    =>  'error',
                    'code'  =>  102,
                    'tips'   => 'error',
                    'message'   => 'base64 Conversion failed',
                    'system_message'    =>  'base64 Conversion failed'
                ];
            }
        }else{
            return [
                'status'    =>  'error',
                'code'  =>  103,
                'tips'   => 'error',
                'message'   => 'Link format error',
                'system_message'    =>  'Link format error'
            ];
        }
	}
    /** 
     ** 图片 转 base64
     *? @date 23/07/11 13:10
     *  @param $image 图片文件 本地图片或远程链接图片
     *  @param myParam2 Explain the meaning of the parameter...
     *! @return base64
     */
    public function imageToBase64() {
        $image = $this->pctco->utils->Request->removeParam($this->file);
        $checkUrl = $this->pctco->safety->verify->open($this->file)->rule('html.href.link')->check();

        $base64 = '';
        if($checkUrl){
            $link = $this->open($image)->save([
                'path'   => 'uploads/temp/',
                'date'   => ['y','m'],
                'enableNotCloudStorage' => false
            ]);
            if ($link['status'] !== 'success') return $link;
            $image = $link['data']['file']['system'];
        }
        $ext = strrchr($image,'.');

        $info = getimagesize($image);

        $data = fread(fopen($image, 'r'), filesize($image));

        $base64 = 'data:' . $info['mime'] . ';base64,' . chunk_split(base64_encode($data));

        if ($checkUrl) {
            $this->pctco->files->utils('file')->utils->open($image,[])->delete();
        }
        return $base64;
    }
}