<?php
namespace pctco\php\safety;
class Verify{
    public function open($data){
        $this->data = $data;
        return $this;
    }
    public function rule($name){
        $rule = [
            'email'  => '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/',
            'markdown.img.link'   =>   '/!\[.*?\]\((.*?(?:[\.gif|\.jpg|\.png|\.jpeg]))\)/',
            'html.href.link'   =>   '/^http(s)?:\\/\\/.+/',
            'html.script.string.img.link'   =>   '/[\'|\"](.*?(?:[\.gif|\.jpg|\.png|\.jpeg]))[\'|\"]/',
            // bug: 无法匹配到url(https://test.jpg) 无引号到链接
            'html.css.string.img.link'   =>   '/url\([\"|\'](.*?[\.gif|\.jpg|\.png|\.jpeg].*?)[\"|\']\)/',
            // 是否是图片格式
            'format.img'   =>   '/.*?(\.png|\.jpg|\.jpeg|\.gif).*?/',
            // 是否是base64 图片格式
            'format.img.base64'   =>   '/^(data:\s*image\/(svg\+xml|fax|gif|x\-icon|jpeg|pnetvue|png|tiff|webp);base64,)/',
            // 是否是链接图片格式 new \Pctco\Storage\App\UploadImage\SaveLinkImage 关联了此规则
            'format.link.img'   =>   '/^(http)(s)?(\:\/\/).*?(\.png|\.jpg|\.jpeg|\.gif|\.ico).*?/',
            // 是否是音频格式
            'format.link.video'   =>   '/^(http)(s)?(\:\/\/).*?(\.mp4|\.wmv|\.webm|\.avi).*?/',

            // 获取链接(html)
            'html.img.src.link'   =>   '/<[img|IMG].*?src=[\'|\"](.*?(?:[\.gif|\.jpg|\.png|\.jpeg]))[\'|\"].*?[\/]?>/',
            'html.img.src.base64'   =>   '/<[img|IMG].*?src=[\'|\"](data:image.*?;base64.*?(?:[\="]))[\'|\"].*?[\/]?>/',
            'html.video.src.link'   =>   '/<[video|VIDEO].*?src=[\'|\"](.*?(?:[\.mp4]))[\'|\"|\?].*?[\/]?/',
            'html.script.src.link'   =>   '/<[script|SCRIPT].*?src=[\'|\"](.*?(?:[\.js]))[\'|\"|\?].*?[\/]?>/',
            'html.script.content'   =>   '/<script.*?>(.*?)<\/script>/is',
            'html.css.href.link'   =>   '/<[link|LINK].*?href=[\'|\"](.*?(?:[\.css]))[\'|\"|\?].*?[\/]?>/',
            'html.a.href.link'   =>   '/<[a|A].*?href="(.*?)".*?>/is',
            'html.img.href.link'   =>   '/<[link|LINK].*?href=[\'|\"](.*?(?:[\.ico|\.gif|\.jpg|\.png|\.jpeg]))[\'|\"|\?].*?[\/]?>/',
        ];
        $this->rule = $rule[$name];
        return $this;
    }
    public function check() :Bool {
        return preg_match($this->rule,$this->data);
    }
    public function find(){
        preg_match_all($this->rule,$this->data,$result);
        $result = empty($result[1]) ? [] : array_unique($result[1]);
        return empty($result)?false:$result;
    }
    public function isEmail(){
        return strlen(filter_var($this->data,FILTER_VALIDATE_EMAIL)) === 0?false:true;
    }
    public function isNumber(){
        return is_numeric($this->data);
    }
    public function isPhone(int $itac = 86) :Array {
        $regexp = [
           86   =>  '/^(134|135|136|137|138|139|147|150|151|152|157|158|159|172|178|182|183|184|187|188|195|198|186|185|155|156|130|131|132|176|175|166|133|153|189|181|180|177|173|199|191)\d{8}$/'
        ];

        if (empty($regexp[$itac])) return [];
        preg_match_all($regexp[$itac],$this->data,$result);

        if (empty($result[1][0])) {
            return [];
        }else{
            $operator = 'unknown';
            if ($itac === 86) {
                if (in_array($result[1][0],[130,131,132,155,156,157])) $operator = 'China Unicom';
                if (in_array($result[1][0],[133,153,173])) $operator = 'China Telecom';
                if (in_array($result[1][0],[134,135,136,137,138,139,150,151,152,153,154,155,156,157,158,159,188])) $operator = 'China Mobile';
            }
            return [
                'operator'  =>  $operator
            ];
        } 
    }
}