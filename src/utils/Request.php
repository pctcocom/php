<?php
namespace pctco\php\utils;
class Request{
    public function domain(string $url = '') :array {
        $parse = parse_url($url);
  
        $host = '';
        $secondary = '';
  
        if (!empty($parse['host'])) {
           $array = explode('.',$parse['host']);
           if (count($array) === 3) {
              $host = $array[1].'.'.$array[2];
              $secondary = $parse['host'];
           }else{
              $host = $parse['host'];
           }
        }
  
        return [
           'host'   => $host,
           'secondary' => $secondary
        ];
    }
    public function removeParam(string $url = '',string $symbol = '?') :String {
        $pos = strpos($url,$symbol);
        if ($pos !== false) {
            $param = substr($url,$pos);
            $url = str_replace($param,'',$url,$count);
            return $url;
        }
        return $url;
     }
}