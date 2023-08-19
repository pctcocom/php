<?php
namespace pctco\php;
class App{
    const VERSION = '1.0.0';
    public function __construct(){
        $SERVER_DOCUMENT_ROOT = $_SERVER['DOCUMENT_ROOT'];
        $this->getRootPath = dirname($SERVER_DOCUMENT_ROOT,1);
        $this->getEntrancePath = $SERVER_DOCUMENT_ROOT;
       
        if (empty($this->getEntrancePath)) {
            $this->getRootPath = $_SERVER['PWD'];
            $this->getEntrancePath = $this->getRootPath.'/public';
        }
    }
}