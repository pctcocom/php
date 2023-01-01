<?php
namespace pctco\php\api\tencent\wechat;
class Tools {
    public function __construct(){
		$this->client = new \GuzzleHttp\Client();
        $this->api = 'https://api.weixin.qq.com/cgi-bin/';
        $this->appid = 'wx3ac7b5dca4a7d6ba';
        $this->secret = '7d7b0014b1455ebbc2abf5f5fe4b6566';
	}
    /** 
     ** 获取Access token
     *? @date 22/12/13 14:43
     *? @url https://developers.weixin.qq.com/doc/offiaccount/Basic_Information/Get_access_token.html
     *! @return Array
     *  
     *  请求成功后返回数据
     *  $data [
     *      "access_token" => "63_JFdsUeXtr6rq4AxCrYM6G3W9aS1UGgum6a9....."
     *      "expires_in" => 7200
     *  ]
     */
    public function getAccessToken(){
        $results = 
        $this->client->request('GET',$this->api.'token',[
            'query'   =>  [
                'grant_type'  =>  'client_credential',
                'appid'  =>  $this->appid,
                'secret'    =>  $this->secret
            ],
            'timeout' => 30
        ]);
        if ($results->getStatusCode() == 200) {
            $results->getHeaderLine('application/json; charset=utf8');
            $results = json_decode($results->getBody()->getContents(),true);
        }else{
            return [
                'status'    =>  'error',
                'code'  =>  100001,
                'tips'   => 'error',
                'message'   => 'GuzzleHttp 请求失败'
            ];
        }
        if (!empty($results['errcode'])) {
            return [
                'status'    =>  'error',
                'code'  =>  $results['errcode'],
                'tips'   => 'error',
                'message'   => $this->errorCode($results['errcode']),
                'system_message'    =>  $results['errmsg']
            ];
        }
        
        return [
            'status'    =>  'success',
            'code'  =>  0,
            'tips'   => 'Success',
            'message'   => '请求成功',
            'data'  =>  $results
        ];
	}
    /** 
     ** 获取微信服务器IP地址
     *? @date 22/12/13 15:15
     *? @url https://developers.weixin.qq.com/doc/offiaccount/Basic_Information/Get_the_WeChat_server_IP_address.html
     *! @return Array
     *  
     *  请求成功后返回数据
     *  1. 微信服务器的 IP 地址列表
     *  2. 建议用户每天请求接口1次
     *  3. 作用：为了安全问题，如果你的服务器对安全设置有要求，可以将这些ip加服务器白名单中
     *  $data [
     *      "ip_list" => array:26 [
                0 => "101.89.47.18"
                1 => "101.91.34.103"
                2 => "101.91.37.13"
                3 => "109.244.129.223"
                4 => "109.244.145.152"
                5 => "109.244.184.250"
                6 => "112.53.42.235"
                7 => "112.60.20.154"
                8 => "112.65.193.153"
                9 => "112.90.80.215"
                10 => "116.128.170.42"
                11 => "116.128.184.169"
                12 => "117.144.228.18"
                13 => "117.144.228.62"
                14 => "119.147.6.203"
                15 => "119.147.6.237"
                16 => "120.232.65.161"
                17 => "157.148.36.94"
                18 => "157.255.218.109"
                19 => "175.27.18.18"
                20 => "175.27.5.221"
                21 => "183.2.143.222"
                22 => "203.205.239.82"
                23 => "203.205.239.94"
                24 => "221.181.99.40"
                25 => "81.69.216.43"
            ]
     *  ]
     */
    public function getApiDomainIp(){
        $token = $this->getAccessToken();
        $results = 
        $this->client->request('GET',$this->api.'get_api_domain_ip',[
            'query'   =>  [
                'access_token'  =>  $token['data']['access_token']
            ],
            'timeout' => 30
        ]);
        if ($results->getStatusCode() == 200) {
            $results->getHeaderLine('application/json; charset=utf8');
            $results = json_decode($results->getBody()->getContents(),true);
        }else{
            return [
                'status'    =>  'error',
                'code'  =>  100001,
                'tips'   => 'error',
                'message'   => 'GuzzleHttp 请求失败'
            ];
        }
        if (!empty($results['errcode'])) {
            return [
                'status'    =>  'error',
                'code'  =>  $results['errcode'],
                'tips'   => 'error',
                'message'   => $this->errorCode($results['errcode']),
                'system_message'    =>  $results['errmsg']
            ];
        }
        
        return [
            'status'    =>  'success',
            'code'  =>  0,
            'tips'   => 'Success',
            'message'   => '请求成功',
            'data'  =>  $results
        ];
    }
    /** 
     ** 获取网络检测结果
     *? @date 22/12/13 15:22
     *? @url https://developers.weixin.qq.com/doc/offiaccount/Basic_Information/Network_Detection.html
     *! @return Array
     *
     * $data = [
     *      "dns" => array:1 [
     *          0 => array:2 [
     *              "ip" => "101.34.43.75"
     *              "real_operator" => "CAP"
     *          ]
     *       ]
     *       "ping" => array:1 [
     *          0 => array:4 [
     *              "ip" => "101.34.43.75"
     *              "from_operator" => "CAP"
     *              "package_loss" => "0%"
     *              "time" => "2.286ms"
     *          ]
     *      ]
     * ]
     * 
     * 
     */
    public function getCallbackCheck(){
        $token = $this->getAccessToken();
        $results = 
        $this->client->request('POST',$this->api.'callback/check',[
            'query'   =>  [
                'access_token'  =>  $token['data']['access_token']
            ],
            'json'  =>  [
                'action'    =>  'all',
                'check_operator'    =>  'DEFAULT'
            ],
            'timeout' => 30
        ]);
        if ($results->getStatusCode() == 200) {
            $results->getHeaderLine('application/json; charset=utf8');
            $results = json_decode($results->getBody()->getContents(),true);
        }else{
            return [
                'status'    =>  'error',
                'code'  =>  100001,
                'tips'   => 'error',
                'message'   => 'GuzzleHttp 请求失败'
            ];
        }
        if (!empty($results['errcode'])) {
            return [
                'status'    =>  'error',
                'code'  =>  $results['errcode'],
                'tips'   => 'error',
                'message'   => $this->errorCode($results['errcode']),
                'system_message'    =>  $results['errmsg']
            ];
        }
        
        return [
            'status'    =>  'success',
            'code'  =>  0,
            'tips'   => 'Success',
            'message'   => '请求成功',
            'data'  =>  $results
        ];
    }
    /** 
     ** 清空 api 的调用quota
     *  1. 本接口用于清空公众号/小程序/第三方平台等接口的每日调用接口次数。
     *  2. 每月只能清理10次
     *  3. 也就是接口权限的每日实时调用量/上限(次)
     *? @date 22/12/13 15:44
     *? @url https://developers.weixin.qq.com/doc/offiaccount/openApi/clear_quota.html
     *  @param myParam1 Explain the meaning of the parameter...
     *  @param myParam2 Explain the meaning of the parameter...
     *! @return Array
     *
     *   $data = [
     *       "errcode" => 0
     *       "errmsg" => "ok"
     *   ]
     *
     */
    public function clearQuota(){
        $token = $this->getAccessToken();
        $results = 
        $this->client->request('POST',$this->api.'clear_quota',[
            'query'   =>  [
                'access_token'  =>  $token['data']['access_token']
            ],
            'json'  =>  [
                'appid' =>  $this->appid
            ],
            'timeout' => 30
        ]);
        if ($results->getStatusCode() == 200) {
            $results->getHeaderLine('application/json; charset=utf8');
            $results = json_decode($results->getBody()->getContents(),true);
        }else{
            return [
                'status'    =>  'error',
                'code'  =>  100001,
                'tips'   => 'error',
                'message'   => 'GuzzleHttp 请求失败'
            ];
        }
        if (!empty($results['errcode'])) {
            return [
                'status'    =>  'error',
                'code'  =>  $results['errcode'],
                'tips'   => 'error',
                'message'   => $this->errorCode($results['errcode']),
                'system_message'    =>  $results['errmsg']
            ];
        }
        
        return [
            'status'    =>  'success',
            'code'  =>  0,
            'tips'   => 'Success',
            'message'   => '请求成功',
            'data'  =>  $results
        ];
    }
    /** 
     ** 查询 openAPI 调用quota
     *  1. 本接口用于查询公众号/小程序/第三方平台等接口的每日调用接口的额度以及调用次数。
     *? @date 22/12/13 15:54
     *? @url https://developers.weixin.qq.com/doc/offiaccount/openApi/get_api_quota.html
     *  @param myParam1 Explain the meaning of the parameter...
     *  @param myParam2 Explain the meaning of the parameter...
     *! @return Array
     *
     *   $data = [
     *       "errcode" => 0
     *       "errmsg" => "ok"
     *       "quota" => array:3 [
     *           "daily_limit" => 500000  - 当天该账号可调用该接口的次数
     *           "used" => 0    - 当天已经调用的次数
     *           "remain" => 500000 - 当天剩余调用次数
     *       ]
     *   ]
     *
     *
     */
    public function getQuota(){
        $token = $this->getAccessToken();
        $results = 
        $this->client->request('POST',$this->api.'openapi/quota/get',[
            'query'   =>  [
                'access_token'  =>  $token['data']['access_token']
            ],
            'json'  =>  [
                'cgi_path' =>  '/cgi-bin/message/custom/send'
            ],
            'timeout' => 30
        ]);
        if ($results->getStatusCode() == 200) {
            $results->getHeaderLine('application/json; charset=utf8');
            $results = json_decode($results->getBody()->getContents(),true);
        }else{
            return [
                'status'    =>  'error',
                'code'  =>  100001,
                'tips'   => 'error',
                'message'   => 'GuzzleHttp 请求失败'
            ];
        }
        if (!empty($results['errcode'])) {
            return [
                'status'    =>  'error',
                'code'  =>  $results['errcode'],
                'tips'   => 'error',
                'message'   => $this->errorCode($results['errcode']),
                'system_message'    =>  $results['errmsg']
            ];
        }
        
        return [
            'status'    =>  'success',
            'code'  =>  0,
            'tips'   => 'Success',
            'message'   => '请求成功',
            'data'  =>  $results
        ];
    }
    /** 
     ** 全局返回码说明
     *? @date 22/12/13 15:34
     *  @param myParam1 Explain the meaning of the parameter...
     *  @param myParam2 Explain the meaning of the parameter...
     *! @return String
     */
    public function errorCode($key){
        $code = [
            -1	=>  '系统繁忙，此时请开发者稍候再试',
            40001   =>  '获取 access_token 时 AppSecret 错误，或者 access_token 无效。请开发者认真比对 AppSecret 的正确性，或查看是否正在为恰当的公众号调用接口',
            40002	=>  '不合法的凭证类型',
            40003	=>  '不合法的 OpenID ，请开发者确认 OpenID （该用户）是否已关注公众号，或是否是其他公众号的 OpenID',
            40004	=>  '不合法的媒体文件类型',
            40005	=>  '不合法的文件类型',
            40006	=>  '不合法的文件大小',
            40007	=>  '不合法的媒体文件 id',
            40008	=>  '不合法的消息类型',
            40009	=>  '不合法的图片文件大小',
            40010	=>  '不合法的语音文件大小',
            40011	=>  '不合法的视频文件大小',
            40012	=>  '不合法的缩略图文件大小',
            40013	=>  '不合法的 AppID ，请开发者检查 AppID 的正确性，避免异常字符，注意大小写',
            40014	=>  '不合法的 access_token ，请开发者认真比对 access_token 的有效性（如是否过期），或查看是否正在为恰当的公众号调用接口',
            40015	=>  '不合法的菜单类型',
            40016	=>  '不合法的按钮个数',
            40017	=>  '不合法的按钮类型',
            40018	=>  '不合法的按钮名字长度',
            40019	=>  '不合法的按钮 KEY 长度',
            40020	=>  '不合法的按钮 URL 长度',
            40021	=>  '不合法的菜单版本号',
            40022	=>  '不合法的子菜单级数',
            40023	=>  '不合法的子菜单按钮个数',
            40024	=>  '不合法的子菜单按钮类型',
            40025	=>  '不合法的子菜单按钮名字长度',
            40026	=>  '不合法的子菜单按钮 KEY 长度',
            40027	=>  '不合法的子菜单按钮 URL 长度',
            40028	=>  '不合法的自定义菜单使用用户',
            40029	=>  '无效的 oauth_code',
            40030	=>  '不合法的 refresh_token',
            40031	=>  '不合法的 openid 列表',
            40032	=>  '不合法的 openid 列表长度',
            40033	=>  '不合法的请求字符，不能包含 \uxxxx 格式的字符',
            40035	=>  '不合法的参数',
            40038	=>  '不合法的请求格式',
            40039	=>  '不合法的 URL 长度',
            40048	=>  '无效的url',
            40050	=>  '不合法的分组 id',
            40051	=>  '分组名字不合法',
            40060	=>  '删除单篇图文时，指定的 article_idx 不合法',
            40117	=>  '分组名字不合法',
            40118	=>  'media_id 大小不合法',
            40119	=>  'button 类型错误',
            40120	=>  '子 button 类型错误',
            40121	=>  '不合法的 media_id 类型',
            40125	=>  '无效的appsecret',
            40132	=>  '微信号不合法',
            40137	=>  '不支持的图片格式',
            40155	=>  '请勿添加其他公众号的主页链接',
            40163	=>  'oauth_code已使用',
            40227	=>  '标题为空',
            41001	=>  '缺少 access_token 参数',
            41002	=>  '缺少 appid 参数',
            41003	=>  '缺少 refresh_token 参数',
            41004	=>  '缺少 secret 参数',
            41005	=>  '缺少多媒体文件数据',
            41006	=>  '缺少 media_id 参数',
            41007	=>  '缺少子菜单数据',
            41008	=>  '缺少 oauth code',
            41009	=>  '缺少 openid',
            42001	=>  'access_token 超时，请检查 access_token 的有效期，请参考基础支持 - 获取 access_token 中，对 access_token 的详细机制说明',
            42002	=>  'refresh_token 超时',
            42003	=>  'oauth_code 超时',
            42007	=>  '用户修改微信密码， accesstoken 和 refreshtoken 失效，需要重新授权',
            42010	=>  '相同 media_id 群发过快，请重试',
            43001	=>  '需要 GET 请求',
            43002	=>  '需要 POST 请求',
            43003	=>  '需要 HTTPS 请求',
            43004	=>  '需要接收者关注',
            43005	=>  '需要好友关系',
            43019	=>  '需要将接收者从黑名单中移除',
            44001	=>  '多媒体文件为空',
            44002	=>  'POST 的数据包为空',
            44003	=>  '图文消息内容为空',
            44004	=>  '文本消息内容为空',
            45001	=>  '多媒体文件大小超过限制',
            45002	=>  '消息内容超过限制',
            45003	=>  '标题字段超过限制',
            45004	=>  '描述字段超过限制',
            45005	=>  '链接字段超过限制',
            45006	=>  '图片链接字段超过限制',
            45007	=>  '语音播放时间超过限制',
            45008	=>  '图文消息超过限制',
            45009	=>  '接口调用超过限制',
            45010	=>  '创建菜单个数超过限制',
            45011	=>  'API 调用太频繁，请稍候再试',
            45015	=>  '回复时间超过限制',
            45016	=>  '系统分组，不允许修改',
            45017	=>  '分组名字过长',
            45018	=>  '分组数量超过上限',
            45047	=>  '客服接口下行条数超过上限',
            45064	=>  '创建菜单包含未关联的小程序',
            45065	=>  '相同 clientmsgid 已存在群发记录，返回数据中带有已存在的群发任务的 msgid',
            45066	=>  '相同 clientmsgid 重试速度过快，请间隔1分钟重试',
            45067	=>  'clientmsgid 长度超过限制',
            45110	=>  '作者字数超出限制',
            46001	=>  '不存在媒体数据',
            46002	=>  '不存在的菜单版本',
            46003	=>  '不存在的菜单数据',
            46004	=>  '不存在的用户',
            47001	=>  '解析 JSON/XML 内容错误',
            47003	=>  '参数值不符合限制要求，详情可参考参数值内容限制说明',
            48001	=>  'api 功能未授权，请确认公众号已获得该接口，可以在公众平台官网 - 开发者中心页中查看接口权限',
            48002	=>  '粉丝拒收消息（粉丝在公众号选项中，关闭了 “ 接收消息 ” ）',
            48004	=>  'api 接口被封禁，请登录 mp.weixin.qq.com 查看详情',
            48005	=>  'api 禁止删除被自动回复和自定义菜单引用的素材',
            48006	=>  'api 禁止清零调用次数，因为清零次数达到上限',
            48008	=>  '没有该类型消息的发送权限',
            48021	=>  '自动保存的草稿无法预览/发送，请先手动保存草稿',
            50001	=>  '用户未授权该 api',
            50002	=>  '用户受限，可能是违规后接口被封禁',
            50005	=>  '用户未关注公众号',
            53500	=>  '发布功能被封禁',
            53501	=>  '频繁请求发布',
            53502	=>  'Publish ID 无效',
            53600	=>  'Article ID 无效',
            61451	=>  '参数错误 (invalid parameter)',
            61452	=>  '无效客服账号 (invalid kf_account)',
            61453	=>  '客服帐号已存在 (kf_account exsited)',
            61454	=>  '客服帐号名长度超过限制 ( 仅允许 10 个英文字符，不包括 @ 及 @ 后的公众号的微信号 )(invalid   kf_acount length)',
            61455	=>  '客服帐号名包含非法字符 ( 仅允许英文 + 数字 )(illegal character in     kf_account)',
            61456	=>  '客服帐号个数超过限制 (10 个客服账号 )(kf_account count exceeded)',
            61457	=>  '无效头像文件类型 (invalid   file type)',
            61450	=>  '系统错误 (system error)',
            61500	=>  '日期格式错误',
            63001	=>  '部分参数为空',
            63002	=>  '无效的签名',
            65301	=>  '不存在此 menuid 对应的个性化菜单',
            65302	=>  '没有相应的用户',
            65303	=>  '没有默认菜单，不能创建个性化菜单',
            65304	=>  'MatchRule 信息为空',
            65305	=>  '个性化菜单数量受限',
            65306	=>  '不支持个性化菜单的帐号',
            65307	=>  '个性化菜单信息为空',
            65308	=>  '包含没有响应类型的 button',
            65309	=>  '个性化菜单开关处于关闭状态',
            65310	=>  '填写了省份或城市信息，国家信息不能为空',
            65311	=>  '填写了城市信息，省份信息不能为空',
            65312	=>  '不合法的国家信息',
            65313	=>  '不合法的省份信息',
            65314	=>  '不合法的城市信息',
            65316	=>  '该公众号的菜单设置了过多的域名外跳（最多跳转到 3 个域名的链接）',
            65317	=>  '不合法的 URL',
            87009	=>  '无效的签名',
            9001001	=>  'POST 数据参数不合法',
            9001002	=>  '远端服务不可用',
            9001003	=>  'Ticket 不合法',
            9001004	=>  '获取摇周边用户信息失败',
            9001005	=>  '获取商户信息失败',
            9001006	=>  '获取 OpenID 失败',
            9001007	=>  '上传文件缺失',
            9001008	=>  '上传素材的文件类型不合法',
            9001009	=>  '上传素材的文件尺寸不合法',
            9001010	=>  '上传失败',
            9001020	=>  '帐号不合法',
            9001021	=>  '已有设备激活率低于 50% ，不能新增设备',
            9001022	=>  '设备申请数不合法，必须为大于 0 的数字',
            9001023	=>  '已存在审核中的设备 ID 申请',
            9001024	=>  '一次查询设备 ID 数量不能超过 50',
            9001025	=>  '设备 ID 不合法',
            9001026	=>  '页面 ID 不合法',
            9001027	=>  '页面参数不合法',
            9001028	=>  '一次删除页面 ID 数量不能超过 10',
            9001029	=>  '页面已应用在设备中，请先解除应用关系再删除',
            9001030	=>  '一次查询页面 ID 数量不能超过 50',
            9001031	=>  '时间区间不合法',
            9001032	=>  '保存设备与页面的绑定关系参数错误',
            9001033	=>  '门店 ID 不合法',
            9001034	=>  '设备备注信息过长',
            9001035	=>  '设备申请参数不合法',
            9001036	=>  '查询起始值 begin 不合法'
        ];

        return empty($code[$key])?'未知错误':$code[$key];
    }
}