<?php
namespace pctco\php;
class Helper{
   public static $_pctco;
   public static function utilsArr(){
      return new utils\Arr;
   }
   public static function dataPath(string $json,string $dataType = 'path',array $data = []){
      $filePath = dirname(__DIR__,4).'/data/json/'.$json;
      if ($dataType === 'path') return $filePath;
      if ($dataType === 'get-array') return json_decode(file_get_contents($filePath),true);
      if ($dataType === 'save-json') file_put_contents($filePath,json_encode($data));
   }
   public static function pctco(array $array){
      $app = new App;
      $utilsArr = self::utilsArr();
      $AllClasses = [
         'utils' => [
            'Arr' => $utilsArr,
            'Str' => new \pctco\php\utils\Str,
            'Request' => new \pctco\php\utils\Request,
            'Date' => new \pctco\php\utils\Date
         ],
         'request'  => [
            'ip'  => new \pctco\php\request\ip\Tools,
         ],
         'files'  => new \pctco\php\files\Tools,
         'spider'  => new \pctco\php\spider\Tools,
         'safety' => [
            'algorithm'  => new \pctco\php\safety\algorithm\Tools,
            'verify' => new \pctco\php\safety\Verify
         ],
         'orm'  => [
            'redis'  => new \pctco\php\orm\redis\Tools,
            'elastic'  => new \pctco\php\orm\elastic\Tools,
         ],
         'api' => [
            'baidu'   => [
               'tongji' => new \pctco\php\api\baidu\tongji\Tools
            ]
         ]
      ];

      $pctco = [
         'app' => [
            'path'   => [
               'root'   => $app->getRootPath
            ]
         ]
      ];
      foreach ($array as $key => $value) {
         if (is_array($value)) {
            foreach ($value as $kc => $vc) {
               if (is_array($vc)) {
                  foreach ($vc as $vp) {
                     $pctco[$key][$kc][$vp] = $AllClasses[$key][$kc][$vp];
                  }
               }else{
                  $pctco[$key][$vc] = $AllClasses[$key][$vc];
               }
            }
         }else{
            $pctco[$value] = $AllClasses[$value];
         } 
      }
      return $utilsArr->obj($pctco);
   }
   public static function request(array $request,$error,$app = 'global'){
      $client = new \GuzzleHttp\Client();

      $results = 
      $client->request($request['method'],$request['action'],$request['options']);
      
      if ($results->getStatusCode() == 200) {
         $results->getHeaderLine('application/json; charset=utf8');
         $results = json_decode($results->getBody()->getContents(),true);
      }else{
         return [
            'status'    =>  'error',
            'code'  =>  100001,
            'tips'   => 'error',
            'message'   => self::errorCode(100001,$app),
            'system_message'    =>  'The GuzzleHttp request failed. Procedure'
         ];
      }

      if (!empty($results[$error['code']])) {
         return [
            'status'    =>  'error',
            'code'  =>  $results[$error['code']],
            'tips'   => 'error',
            'message'   => self::errorCode($results[$error['code']],$app),
            'system_message'    =>  $results[$error['msg']]
         ];
      }
      
      return [
         'status'    =>  'success',
         'code'  =>  0,
         'tips'   => 'success',
         'message'   => self::errorCode(0,$app),
         'system_message'    =>  'Data request success',
         'data'  =>  $results
      ];
   }
   public static function config($helper = 'get'){
      $dir = self::dataPath('extend.json');

      /** 
       ** get extend_config.json
       *? @date 22/12/21 20:55
       *! @return obj
       */
      if (is_string($helper)) {
         $helper = explode('::',$helper);
         $utilsArr = self::utilsArr();

         if ($helper[0] === 'get') {
            unset($helper[0]);
            $json = json_decode(file_get_contents($dir),true);
            return $utilsArr->findLadderNode($json,$helper);
         }
         return false;
      }

      /** 
       ** set extend.json
       *? @date 22/12/21 20:54
       *! @return boolean
       */
      if (is_object($helper)) {
         $data = json_encode($helper);
         file_put_contents($dir,$data);
         return $data;
      }
      return false;
   }
   /** 
    ** 全局错误代码
    *? @date 22/12/23 13:17
    *  @param myParam1 Explain the meaning of the parameter...
    *  @param myParam2 Explain the meaning of the parameter...
    *! @return 
    */
   public static function errorCode($key,string $app = 'global'){

      if ($app === 'api::baidu::tongji') {
         $code = [
            110 =>  '访问令牌无效或不再有效',
            111   => '访问令牌过期'
         ];
      }

      if (empty($code[$key])) {
         /** 
          ** 全局错误代码
          *? @date 22/12/23 16:02 
          */
         $code = [
            -100001   => 'GuzzleHttp 请求失败',
            0  => '数据请求成功',
         ];

         return empty($code[$key])?'未知错误':$code[$key];
      }
   }
}