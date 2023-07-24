<?php
namespace pctco\php\files;
use pctco\php\Helper;
class Tools{
    public function utils($apps){
        $utils = [
            'text'   =>  [
                'MarkdownToHTML'   =>  new text\MarkdownToHTML\Apps,
                'HTMLToMarkdown'  =>  new text\HTMLToMarkdown\Apps,
                'html'  =>  new text\html\Apps
            ],
            'file' =>  [
                'utils'  =>  new file\Utils
            ]
        ];
        return Helper::utilsArr()->obj($utils[$apps]);
    }
}