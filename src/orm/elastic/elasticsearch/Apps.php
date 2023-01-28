<?php
namespace pctco\php\orm\elastic\elasticsearch;
use Elasticsearch\ClientBuilder;
use pctco\php\Helper;
class Apps{
   function __construct(object $config){
      /** 
       ** 配置setHosts
       *? @date 23/01/17 19:22
       */
      // 没有手动设置配置，则调用extend.json配置
      if (empty($option['hosts']['connection'])) $option['hosts']['connection'] = 'main';
      $config = (array)$config;
      $this->config = $config[$option['hosts']['connection']];
      $this->client = ClientBuilder::create()->setHosts((array)$this->config)->build();

   }
   /** 
    ** 索引名称
    */
   public function db($index){
      $this->params = [
         'index'  => $index
      ];
      
      return Helper::utilsArr()->obj([
         'index'  => new Index($this),
         'mappings'  => new Mappings($this),
         'data'   => new Data($this)
      ]);
   }
   /** 
    ** 获取elasticsearch信息（#）
    *? @date 23/01/18 15:02
    *! @return Array
    */
   public function info(){
      return $this->client->info();
   }
   public function plugins(){
      return $this->client->cat()->plugins();
   }
   public function test(){
      // return $this->client->build();

      
      // 搜索
      // $a = $this->client->search([
      //    'index'  => $this->indexs['index'],
      //    'scroll' => '1m',
      //    'body' => [
      //       'query' => [
      //           'match' => [
      //                'title'  => '小'
      //           ],
      //       ]
      //    ]
      // ]);
      // 通过scroll直接获取$a的数据
      // $b = $this->client->scroll([
      //    'scroll' => '1m',
      //    'scroll_id' => $a['_scroll_id']
      // ]);



      // return [
      //    'a'   => $a,
      //    'b'   => $b
      // ];

      // 查看集群的健康状况 http://localhost:9200/_cat/
      /** 
       *! 说明：v是用来要求在结果中返回表头 
       ** http://localhost:9200/_cat/?v - 查看集群的节点
       ** http://localhost:9200/_cat/indices?v - 查看所有索引
       ** http://localhost:9200/my_110/_search?q=*&sort=id:asc&pretty - 查询所有文档
       */
      // $a = $this->client->cat()->indices();

      // return [
      //    'a'   => $a
      // ];
   }
}
