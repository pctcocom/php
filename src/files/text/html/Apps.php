<?php
declare(strict_types=1);
namespace pctco\php\files\text\html;
use pctco\php\Helper;
class Apps {
    public function result(string $html): object{
        return Helper::utilsArr()->obj([
            'utils' =>  new Utils($html)
        ]);
    }
}