<?php
namespace Pctco\php\api\tencent\wechat;
use pctco\php\api\tencent\wechat\Tools;
class Menu{
    public function __construct(){
        $this->tools = new Tools();
		$this->token = $this->tools->getAccessToken();
    }
	/** 
	 ** 创建接口
	 *? @date 22/12/14 14:04
	 *? @url https://developers.weixin.qq.com/doc/offiaccount/Custom_Menus/Creating_Custom-Defined_Menu.html
	 *! @return 

		$data = [
			"errcode" => 0
    		"errmsg" => "ok"
		]

	 */
    public function create(){
		if ($this->token['status'] !== 'success') {
			return 'error';
		}else{
			$menus = [
				'button'	=>	[
					[
						'type'	=>	'click',
						'name'	=>	'热门资讯',
						'key'	=>	'V1001_TODAY_MUSIC'
					],
					[
						'name'	=>	'菜单2',
						'sub_button'	=>	[
							[
								'type'	=>	'view',
								'name'	=>	'搜狗',
								'url'	=>	'http://www.soso.com/'
							],
							[
								'type'	=>	'view',
								'name'	=>	'百度',
								'url'	=>	'http://www.baidu.com/'
							]
						]
					]
				]
			];
			
			$results = 
			$this->tools->client->request('POST',$this->tools->api.'menu/create?access_token='.$this->token['data']['access_token'],['body'	=>	json_encode($menus,JSON_UNESCAPED_UNICODE)]);
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
					'message'   => $this->tools->errorCode($results['errcode']),
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
	}
	/** 
	 ** 查询接口
	 *? @date 22/12/14 14:04
	 *? @url https://developers.weixin.qq.com/doc/offiaccount/Custom_Menus/Querying_Custom_Menus.html
	 *! @return Array


		$data = [
			"is_menu_open" => 1
			"selfmenu_info" => array:1 [
			"button" => array:2 [
				0 => array:3 [
				"type" => "click"
				"name" => "热门资讯"
				"key" => "V1001_TODAY_MUSIC"
				]
				1 => array:2 [
				"name" => "菜单2"
				"sub_button" => array:1 [
					"list" => array:2 [
					0 => array:3 [
						"type" => "view"
						"name" => "搜狗"
						"url" => "http://www.soso.com/"
					]
					1 => array:3 [
						"type" => "view"
						"name" => "百度"
						"url" => "http://www.baidu.com/"
					]
					]
				]
				]
			]
			]
		]


	 */
    public function getCurrentSelfmenuInfo(){
		$results = 
		$this->tools->client->request('GET',$this->tools->api.'get_current_selfmenu_info',[
			'query'   =>  [
				'access_token'  =>  $this->token['data']['access_token']
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
				'message'   => $this->tools->errorCode($results['errcode']),
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
	 ** 删除接口
	 *? @date 22/12/14 15:42
	 *? @url https://developers.weixin.qq.com/doc/offiaccount/Custom_Menus/Deleting_Custom-Defined_Menu.html
	 *! @return 

		$data = [
			"errcode" => 0
    		"errmsg" => "ok"
		]

	 */
	public function delete(){
		$results = 
		$this->tools->client->request('GET',$this->tools->api.'menu/delete',[
			'query'   =>  [
				'access_token'  =>  $this->token['data']['access_token']
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
				'message'   => $this->tools->errorCode($results['errcode']),
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
}
