<?php
declare(strict_types=1);
namespace pctco\php\files\text\MarkdownToHTML;
use pctco\php\Helper;
class Apps {
    public function result(array $config,string $markdown): object{
        $config = array_merge([
            'terminal'  =>  [
                'template'  =>  false
            ],
            'model'  =>  [
                'module'  =>  [],
                'dir'   =>  ''
            ],
            'ad'  => false,
            'safety'    =>  [
                // 预防
                'prevent'   =>  [
                    // 预防采集
                    'collection'    =>  false
                ]
            ],
            'toc'   =>  [
                // string(html) // json
                'type'  =>  false
            ]
        ],$config);
        if ($config['terminal']['template'] !== false) {
            $config['terminal']['template'] = file_get_contents($config['terminal']['template']);
        }

        $converter = new Main(Helper::utilsArr()->obj($config));
        $html = $converter->text($markdown);
        $toc = $config['toc']['type'] === false?$config['toc']['type']:$converter->contentsList($html);
        return Helper::utilsArr()->obj([
            'html'  =>  $html,
            'toc'   =>  $toc
        ]);
    }
}
