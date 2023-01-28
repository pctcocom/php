<?php
namespace pctco\php\orm\elastic\elasticsearch;
class Index{
    public function __construct($apps){
        $this->apps = $apps;
    }
   /** 
    ** 创建索引（#）
    */
    public function create(array $body = []){
        try {
            return 
            $this->apps->client->indices()->create([
                'index' =>  $this->apps->params['index'],
                'body'  => $body
            ]);
        } catch (\Exception $e) {
            $msg = $e->getMessage();
            $msg = json_decode($msg,true);
            return $msg['error']['reason'];
        }
    }
    /** 
     ** 获取索引信息（#）
     */
    public function get(){
        try {
            $params = [
                'index' =>  $this->apps->params['index']
            ];
            return $this->apps->client->indices()->getSettings($params);
        } catch (\Exception $e) {
            // $e->getLine()
            // return $e->getFile();
            return $e->getMessage();
        }
    }
    /** 
     ** 删除索引（#）
     */
    public function delete(){
        try {
            $result = $this->apps->client->indices()->delete([
                'index'   =>   $this->apps->params['index']
            ]);
            return $result['acknowledged'];
        } catch (\Exception $e) {
            return $e->getMessage();
            $msg = $e->getMessage();
            $msg = json_decode($msg,true);
            return $msg['error']['reason'];
        }
    }
}
