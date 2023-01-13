<?php
declare(strict_types=1);
namespace pctco\php\files\text\HTMLToMarkdown;
use pctco\php\Helper;
class Apps {
    public function result(string $html): object{
        $converter = new HtmlConverter;
        return Helper::utilsArr()->obj([
            'markdown' =>  $converter->convert($html)
        ]);
    }
}
