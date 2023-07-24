<?php
declare(strict_types=1);
namespace pctco\php\files\text\html;
use pctco\php\Helper;
class Utils {
    public function __construct(string $html = ''){
        $this->pctco = Helper::pctco([
            'utils' =>  ['Arr'],
            'safety' => ['algorithm','verify']
        ]);
        $this->html = $html;
        $this->jwt = $this->pctco->safety->algorithm->utils()->token->utils('jwt');
    }
    public function hrefEncryption(array $options = []){
        $options = 
        $this->pctco->utils->Arr->merge([],[
            'domain' =>  'test.com',
            'urls'   =>  '//www.{{domain}}.com/link/{{url}}'
        ],$options);

        $options = 
        $this->pctco->utils->Arr->obj($options);

        if ($this->html === '') return $this->html;
        $links = $this->pctco->safety->verify->open($this->html)->rule('html.a.href.link')->find();
        $urls = str_replace('{{domain}}',$options->domain,$options->urls);
        if (!empty($links)) {
            $original = [];
            $new = [];
            foreach ($links as $url) {
                if (strpos($url,$options->domain) === false && $this->pctco->safety->verify->open($url)->rule('html.href.link')->check()) {
                    $original[] = 'href="'.$url.'"';
                    $new[] = 'href="'.str_replace('{{url}}',$this->jwt->encode($url),$urls).'" target="_blank"';
                }
            }
            return str_replace($original,$new,$this->html);
        }
        return $this->html;
    }
}