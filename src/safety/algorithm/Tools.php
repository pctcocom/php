<?php
namespace pctco\php\safety\algorithm;
use pctco\php\Helper;
class Tools{
    public function __construct(){
        $this->config = Helper::config('get::safety::algorithm');
	}
    public function utils(){
        return Helper::utilsArr()->obj([
            'num'   =>  new num\Tools($this->config->num),
            'aes' => new AES
        ]);
    }
}