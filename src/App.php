<?php
namespace pctco\php;
class App{
    const VERSION = '1.0.0';
    public function __construct(){
        $SERVER_DOCUMENT_ROOT = $_SERVER['DOCUMENT_ROOT'];
        $this->getRootPath = dirname($SERVER_DOCUMENT_ROOT,1).'/';
    }
}