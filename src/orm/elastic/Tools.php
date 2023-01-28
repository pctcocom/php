<?php
namespace pctco\php\orm\elastic;
use pctco\php\Helper;
class Tools{
   public function __construct(){
      $this->config = Helper::config('get::orm::elastic');
      $this->es = new elasticsearch\Apps($this->config->elasticsearch);
	}
}