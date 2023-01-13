<?php
namespace pctco\php\request\ip;
use pctco\php\Helper;
class Tools{
    public function __construct(){
        $this->config = Helper::config('get::request::ip');
	}
    public function data(string $ip = '127.0.0.1',array $is = [],string $spacer = '/') :Array {

        $nlname = ['country','province','city'];
        
        if ($this->config->library->name === 'dba') {
            $apps = new dba\Apps;
            $data = array_filter(array_unique($apps::find($ip)));
            $str = implode($spacer,$data);
            $arr = [];
            foreach ($data as $k=>$d) {
                if (empty($nlname[$k])) {
                    $arr['n'.$k] = $d;
                }else{
                    $arr[$nlname[$k]] = $d;
                }
            }

            $isResult = false;
            if (!empty($is)) {
                if (!empty($arr[$is[0]])) {
                    if ($arr[$is[0]] === $is[1]) {
                        $isResult = true;
                        if (!empty($is[2])) {
                            $s3Data = [];
                            foreach (explode(',',$is[2]) as $s3) {
                                $s3Data[] = strpos($str,$s3);
                            }
                            $isResult = empty(array_filter($s3Data));
                        }
                    }
                } 
            }

            return Helper::utilsArr()->obj([
                'ip'    =>  $ip,
                'arr' =>  $arr,
                'str'    =>  $str,
                'is'  =>  $isResult
            ]);
        }

        return [];
	}
}