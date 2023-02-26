<?php
namespace pctco\php\files\file;
use SplFileInfo;
use SplFileObject;
use pctco\php\Helper;
class Utils{
    // 默认权限等级
    public function open(string $file = '',array $config = []){
        $this->file = new SplFileInfo($file);

        $this->config = Helper::utilsArr()->obj(Helper::utilsArr()->merge([],[
            'date'  =>  [
                'formatting'    =>  'Y-m-d H:i:s'
            ],
            'spl'   =>  [
                'obj'   =>  [
                    'open'  =>  false,
                    'mode'  =>  'w+'
                ]
            ]
        ],$config));

        if ($this->config->spl->obj->open) {
            $this->mkdirs();
            $this->obj = new SplFileObject($file,$this->config->spl->obj->mode);
        }

        return $this;
    }
    public function exists(){
        $return = false;
        if ($this->file->isFile() || $this->file->isDir()) $return = true;
        return $return;
    }
    public function chmod($fileMode){
        if ($this->exists()) {
            // 文件模式必须来自类型八进制。通过将八进制转换为十进制，或者反过来
            // 我们要确定给定的值是八进制。任何非八进制数都会被检测到。
            if (decoct(octdec($fileMode)) != $fileMode) {
                // Chmod失败，因为给予的权限不是来自类型八进制。
                return false;
            }

            // 将给定的八进制字符串转换为八进制整数
            if (is_string($fileMode)) {
                $fileMode = intval($fileMode, 8);
            }

            switch ($fileMode) {
                case 0600: // file owner read and write;
                case 0640: // file owner read and write; owner group read
                case 0660: // file owner read and write; owner group read and write
                case 0604: // file owner read and write; everbody read
                case 0606: // file owner read and write; everbody read and write
                case 0664: // file owner read and write; owner group read and write; everbody read
                case 0666: // file owner read and write; owner group read and write; everbody read and write
                case 0700: // file owner read, execute and write;
                case 0740: // file owner read, execute and write; owner group read
                case 0760: // file owner read, execute and write; owner group read and write
                case 0770: // file owner read, execute and write; owner group read, execute and write
                case 0704: // file owner read, execute and write; everbody read
                case 0706: // file owner read, execute and write; everbody read and write
                case 0707: // file owner read, execute and write; everbody read, execute and write
                case 0744: // file owner read, execute and write; owner group read; everbody read
                case 0746: // file owner read, execute and write; owner group read; everbody read and write
                case 0747: // file owner read, execute and write; owner group read; everbody read, execute and write
                case 0754: // file owner read, execute and write; owner group read and execute; everbody read
                case 0755: // file owner read, execute and write; owner group read and execute; everbody read and execute
                case 0756: // file owner read, execute and write; owner group read and execute; everbody read and write
                case 0757: // file owner read, execute and write; owner group read and execute; everbody read, execute and write
                case 0764: // file owner read, execute and write; owner group read and write; everbody read
                case 0766: // file owner read, execute and write; owner group read and write; everbody read and write
                case 0767: // file owner read, execute and write; owner group read and write; everbody read, execute and write
                case 0774: // file owner read, execute and write; owner group read, execute and write; everbody read
                case 0775: // file owner read, execute and write; owner group read, execute and write; everbody read, execute and write
                case 0776: // file owner read, execute and write; owner group read, execute and write; everbody read and write
                case 0777: // file owner read, execute and write; owner group read, execute and write; everbody read, execute and write
                    break;
                default:
                    $fileMode = 0777;
            }

            return chmod($this->getPathname(), $fileMode);
        }
        return false;
    }
    public function mkdirs($mode = 0777){
        try {
            if ($this->exists() === false) {
                if (mkdir($this->file->getPath().'/', 0777, true)) {
                    $this->chmod($mode);
                }
            }
        } catch (\Throwable $th) {}
        return $this;
    }
    /** 
     ** file
     *? @date 23/02/26 17:26
     */
    public function time(){
        $getATime = $this->file->getATime();
        return Helper::utilsArr()->obj([
            // 获取文件的最后访问时间
            'last'  =>  [
                'timestamp'  =>  $getATime,
                'date'  =>  date($this->config->date->formatting,$getATime)
            ]
        ]);
    }
    public function isFile(){
        return $this->file->isFile();
    }
    public function isWritable(){
        return $this->file->isWritable();
    }
    public function isDir(){
        return $this->file->isDir();
    }
    public function getFileName(){
        return $this->file->getFilename();
    }
    public function getBaseName($suffix = ''){
        return $this->file->getBasename($suffix);
    }

    /** 
     ** obj
     *? @date 23/02/26 17:26
     */
    public function write($string){
        return $this->obj->fwrite($string);
    }
    /** 
     ** clear
     *? @date 23/02/26 17:52
     *  @param myParam1 Explain the meaning of the parameter...
     *  @param myParam2 Explain the meaning of the parameter...
     *! @return 
     */
    public function clear(){
        $this->obj->ftruncate(0);
        return $this;
    }
}