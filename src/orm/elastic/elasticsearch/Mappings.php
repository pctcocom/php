<?php
namespace pctco\php\orm\elastic\elasticsearch;
class Mappings{
    public function __construct($apps){
        $this->apps = $apps;
    }
    /** 
     ** 创建映射（？）
     *? @date 23/01/17 22:10
     *  @param $name 映射名称
     *! @return Array
     */
    public function put(array $properties){
        return $this->apps->client->indices()->putMapping([
            'index' => $this->apps->params['index'],
            'body' => [
                '_source' => [
                    'enabled' => true
                ],
                'properties' => $properties
            ]
        ]);
    }
    /** 
     ** 获取映射（#）
     *? @date 23/01/17 22:11
     *! @return Array
     */
    public function get() {
        try {
            return $this->apps->client->indices()->getMapping([
                'index' => $this->apps->params['index']
            ]);
        } catch (\Exception $e) {
            $msg = $e->getMessage();
            $msg = json_decode($msg,true);
            return $msg['error']['reason'];
        }
    }
    /** 
     ** 获取字段映射（#）
     *? @date 23/01/18 16:12
     *  @param myParam1 Explain the meaning of the parameter...
     *  @param myParam2 Explain the meaning of the parameter...
     *! @return Array
     */
    public function field(){
        return $this->apps->client->indices()->getFieldMapping([
            'index' => $this->apps->params['index'],
            'fields' => '*'
        ]);
    }
}