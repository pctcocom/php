<?php
namespace pctco\php\orm\elastic\elasticsearch;
class Data{
    private $_order = null;
    private $_orderSelect = null;
    private $_field = true;
    private $_paginate = [
        'page' => 0,
        'size' => 10,
    ];
    private $_query = null;
    public function __construct($apps){
        $this->apps = $apps;
    }
    /** 
     ** 排序
     *? @date 23/01/19 17:11
     *  @param array $_order
     */
    public function order(array $_order){

        /** 
         ** 排序
         *? @date 23/01/19 17:43
         */
        if ($this->_order !== null) {
            $order = [];
            foreach ($this->_order as $orderk => $orderv) {
                $order[$orderk] = [
                    'order' =>  $orderv
                ];
                $this->_orderSelect = $orderk.':'.$orderv;
            }
        }else{
            $order['id'] = [
                'order' =>  'desc'
            ];
            $this->_orderSelect = 'id:desc';
        }

        $this->_order = $order;

        return $this;
    }
    /** 
     ** 指定字段
     *? @date 23/01/25 16:20
     *  @param array $field 需要处理的字段
     *  @param bool $without 字段排除 false = includes（包括），true = excludes（不包括）
     *! @return 
     */
    public function field(array $field,$without = false){
        $without = $without?'excludes':'includes';
        if (is_array($field)) {
            $this->_field = [
                $without  =>  $field
            ];
        }
        return $this;
    }
    /** 
     ** 分页
     *? @date 23/01/19 17:23
     *  @param $page 当前页码
     *  @param $size 每页显示多少条数据
     */
    public function paginate(array $_paginate){
        $this->_paginate = array_merge($this->_paginate,$_paginate);

        $paginate = $this->_paginate;
        $from = 0;
        if ($paginate['page'] > 1) $from = ($paginate['page'] - 1)*$paginate['size'];
        $this->_paginate['form'] = $from;

        return $this;
    }
    /** 
     ** where
     *? @date 23/01/19 17:37
     *  @param $query 查询表达式
     *! @return 
     */
    public function query(array $_query){
        $this->_query = $_query;
        return $this;
    }
    /** 
     ** 插入数据（#）
     */
    public function insert(array $data){
        if (count($data) === count($data, 1)) {
            $this->apps->client->index([
                'index' => $this->apps->params['index'],
                'id' => $data['id'],
                'body' => $data
            ]);
            return true;
        }else{
            foreach ($data as $k => $v) {
                $this->apps->client->index([
                    'index' => $this->apps->params['index'],
                    'id' => $v['id'],
                    'body' => $v
                ]);
            }
            return true;
        }
    }
    /** 
     ** 获取数据（#） 
     */
    public function get($id){
        try {
            $result =  
            $this->apps->client->get([
                'index' => $this->apps->params['index'],
                'id'    =>  $id
            ]);
            return $result['_source'];
        } catch (\Exception $e) {
            // 没有查询到数据
            return false;
        }
    }
    /** 
     ** 获取全部数据（#） 
     *  http://localhost:9200/os/_search?q=*
     */
    public function select(){
        return $this->apps->client->search([
            'index'  => $this->apps->params['index'],
            'q'   => '*',
            'from' => $this->_paginate['form'],
            'size' => $this->_paginate['size'],
            'sort'  =>  $this->_orderSelect
        ]);
    }
    /** 
     ** 搜索数据（#） 
     */
    public function search(){
        /** 
         ** 查询表达式
         *? @date 23/01/19 17:43
         */

        if ($this->_query === null) return '无法判断query字段数据';

        $highlight = [];
        if (empty($this->_query['wildcard'])) {
            // 正常搜索
            $query = [
                'bool' => [
                    'should' => $this->_query,
                ],
            ];
            foreach ($this->_query as $highlightv) {
                $highlight[array_keys($highlightv['match'])[0]] = (object)[];
            }
        }else{
            // 模糊搜索
            $query = $this->_query;
            foreach ($query['wildcard'] as $highlightk => $highlightv) {
                $highlight[$highlightk] = (object)[];
            }
        }
        
        return $this->apps->client->search([
            'index' => $this->apps->params['index'],
            'body' => [
                '_source'   =>  $this->_field,
                'query' =>  $query,
                'sort'  =>  $this->_order,
                'from' => $this->_paginate['form'],
                'size' => $this->_paginate['size'],
                // 高亮显示
                'highlight'=>[
                   "pre_tags" => ['<em>'],
                   "post_tags" => ['</em>'],
                   'fields' =>  $highlight
                ]
            ]
        ]);
    }
    /** 
     ** 判断数据是否存在（#）
     */
    public function exists($id){
        return $this->apps->client->exists([
            'index' => $this->apps->params['index'],
            'id' => $id
        ]);
    }
    /** 
     ** 删除指定索引中的数据（#）
     */
    public function delete($id){
        try {
            $result =  
            $this->apps->client->delete([
                'index' => $this->apps->params['index'],
                'id'    =>  $id
            ]);
            return $id;
        } catch (\Exception $e) {
            // 没有可删除的数据
            return false;
        }
    }
}