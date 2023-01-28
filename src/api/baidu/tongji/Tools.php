<?php
namespace pctco\php\api\baidu\tongji;
use pctco\php\Helper;
class Tools {
    public function __construct(){
        $config = Helper::config('get::api::baidu::tongji');
        $this->api = $config->api;
        $this->appid = $config->appid;
        $this->secret = $config->secret;
        $this->token = $config->token;
	}
    public function utils(){
        return Helper::utilsArr()->obj([
            'Site' => new Site,
            'Data' => new Data
        ]);
    }
    /** 
     ** 查询
     *? @date 23/01/01 14:53
     *  @param array $request 请求查询数据
     *  @param array $error 请求错误字段 ['code'=>'error_code','msg'=>'error_msg']
     *  @param string $app 应用域
     *! @return 
     */
    public function query($request){
        return Helper::request($request,[
            'code'  =>  'error_code',
            'msg'   =>  'error_msg'
        ],'api::baidu::tongji');
    }
}