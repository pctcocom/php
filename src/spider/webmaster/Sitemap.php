<?php
declare(strict_types=1);
namespace pctco\php\spider\webmaster;
use pctco\php\Helper;
class Sitemap{
   private $_config = [];
   /** 
    ** 配置
    *? @date 23/02/26 14:04
    *  @param string $model 数据模型 如 news、books、os
    *  @param string $changefreq 更新频率
            always（总是）
            hourly（每小时）
            daily（每天）
            weekly（每周）
            monthly（每月）
            yearly（每年）
            never（从不）
    *  @param string $host 主机域名 如 https://www.test.com
    *  @param string $path 想要保存xml的文件路径
    *! @return 
    */
   public function config(array $_config = []){
      $this->_config = 
      Helper::utilsArr()->obj(array_merge([
         'model'  => 'news',
         'changefreq'   => 'daily',
         'host' => '',
         'path'   => ''
      ],$_config));
      return $this;
   }
   /** 
    ** Save XML
    *? @date 23/02/26 13:41
    *  @param Array $select 二维数组
    *! @return array
    */
    public function save($select){
      $count = count($select);
      $arr = [
         [
            'xml'   =>   '',
            'name'   =>   'google',
            'count'   =>   $count,
            'webmasters'   =>   'https://search.google.com/search-console'
         ],[
            'xml'   =>   '',
            'name'   =>   'baidu',
            'count'   =>   $count,
            'webmasters'   =>   'https://ziyuan.baidu.com/'
         ],[
            'xml'   =>   '',
            'name'   =>   'so',
            'count'   =>   $count,
            'webmasters'   =>   'https://zhanzhang.so.com/'
         ],[
            'xml'   =>   '',
            'name'   =>   'bing',
            'count'   =>   $count,
            'webmasters'   =>   'https://www.bing.com/webmasters/'
         ],[
            'xml'   =>   '',
            'name'   =>   'sogou',
            'count'   =>   $count,
            'webmasters'   =>   'https://zhanzhang.sogou.com/'
         ]
      ];

      $result = [];
      foreach ($arr as $v) {
         $sitemap = $this->generate($select,$v['name']);
         $v['xml']   = $this->_config->host.$sitemap;
         $result[$v['name']] = $v;
      }

      return $result;
   }
   /** 
    ** 生成并保存 .xml 文件 Sitemap Maps XML
    *? @date 23/02/26 13:14
    *  @param $select 数据
    *  @param $name sitemap 平台名称 如 google、baidu
    *! @return 返回XML保存路径地址
    */
   public function generate($select,$name){
      $xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
      $xml .= "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";
      foreach ($select as $items) {
         $xml .= $this->xml([
            'items'  => $items,
            'format' => $name
         ]);
      }
      $xml .= "</urlset>\n";
      
      switch ($this->_config->changefreq) {
         case 'daily':
            $dates = 'daily_'.date('Ymd',time());
            break;
         case 'weekly':
            $dates = 'week_'.date('Y').'_'.(int)date('W');
            break;
         default:
            return false;
            break;
      }
      $pctco = Helper::pctco(['files']);

      $rootPath = $pctco->app->path->root;
      $path = $this->_config->path.$name.'_'.$this->_config->model.'_'.$dates.'.xml';
      $rootPath = $rootPath.'entrance'.$path;

      $fileUtils = 
      $pctco->files->utils('file')->utils
      ->open($rootPath,[
          'spl' => [
             'obj' => [
                'open'   => true
             ]
          ]
      ]);
      $fileUtils->clear()->write($xml);
      return $path;
   }
   /** 
    ** xml urlset
    *? @date 23/02/26 13:18
    *  @param $items 单条数据
    *! @return 
    */
   public function xml($options = []){
      $options = 
      array_merge([
         'items'  => [],
         'format' => ''
      ],$options);
      switch ($options['format']) {
         case 'baidu':
         case 'so':
         case 'bing':
            $xml = "<url>\n";
            $xml .= "<loc>" . $options['items']['url'] . "</loc>\n";
            $xml .= "<lastmod>" . $options['items']['date'] . "</lastmod>\n";
            $xml .= "<changefreq>".$this->_config->changefreq."</changefreq>\n";
            $xml .= "</url>\n";
            return $xml;
            break;
         case 'sogou':  // 搜狗专用地图（自适应规则文件）
            /** 
               <version>7</version> 必填，填写映射规则适合的版本:
               1. 只适用于简版
               2. 只适用于彩版
               5. 只适用于移动版
               6. 适用于彩版和移动版
               7. 适用于简版、彩版、移动版
             */
            $xml = "<url>\n";
            $xml .= "<loc>" . $this->_config->host . "</loc>\n";
            $xml .= "<data>\n";
            $xml .= "<display>\n";
            $xml .= "<pc_url_pattern>" . $options['items']['pattern'] . "</pc_url_pattern>\n";
            $xml .= "<pc_sample>".$options['items']['url']."</pc_sample>\n";
            $xml .= "<version>7</version>";
            $xml .= "</display>\n";
            $xml .= "</data>\n";
            $xml .= "</url>\n";
            return $xml;
            break;
         case 'google':
            $xml = "<url>\n";
            $xml .= "<loc>" . $options['items']['url'] . "</loc>\n";
            $xml .= "<lastmod>" . $options['items']['date'] . "</lastmod>\n";
            $xml .= "</url>\n";
            return $xml;
            break;
         default:
            # code...
            break;
      }

   }
}
