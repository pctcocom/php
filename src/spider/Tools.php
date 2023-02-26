<?php
namespace pctco\php\spider;
use pctco\php\Helper;
class Tools{
    public function utils($apps){
        $utils = [
            'webmaster'   =>  [
                'sitemap'   =>  new webmaster\Sitemap
            ]
        ];
        return Helper::utilsArr()->obj($utils[$apps]);
    }
}