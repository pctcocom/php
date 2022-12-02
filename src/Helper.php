<?php
namespace pctco\php;
use pctco\php\utils\Arr;
class Helper{
   public static function pctco(array $array){
      $utilsArr = new \pctco\php\utils\Arr;
      $AllClasses = [
         'utils' => [
            'Arr' => $utilsArr,
            'Str' => new \pctco\php\utils\Str,
            'Date' => new \pctco\php\utils\Date
         ]
      ];

      $pctco = [];
      foreach ($array as $key => $value) {
         if (is_array($value)) {
            foreach ($value as $vc) {
               $pctco[$key][$vc] = $AllClasses[$key][$vc];
            }
         }else{
            $pctco[$value] = $AllClasses[$value];
         } 
      }
      return $utilsArr->obj($pctco);
   }
   public function AllClasses(){
      
   }
}