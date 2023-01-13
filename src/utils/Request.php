<?php
namespace pctco\php\utils;
class Request{
    /** 
     ** 获取server参数
     *? @date 22/12/14 16:32
     * @param  string $name 数据名称
     * @param  string $default 默认值
     *! @return mixed
     */
    public function server(string $name = '', string $default = ''){
        if (empty($name)) {
            return $this->server;
        } else {
            $name = strtoupper($name);
        }

        return $this->server[$name] ?? $default;
    }
}