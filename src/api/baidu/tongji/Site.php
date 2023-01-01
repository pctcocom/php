<?php
namespace pctco\php\api\baidu\tongji;
class Site extends Tools{
    public function getList(){
        return Tools::query([
            'method'  =>  'POST',
            'action'   =>  $this->api.'config/getSiteList',
            'options'  =>  [
                'query'   =>  [
                    'access_token'  =>  $this->token
                ],
                'timeout'   =>  30
            ]
        ]);
	}
}