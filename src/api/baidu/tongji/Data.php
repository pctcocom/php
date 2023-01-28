<?php
namespace pctco\php\api\baidu\tongji;
class Data extends Tools{
    /** 
     ** 网站概况(趋势数据)
     *? @date 22/12/13 14:43
     *? @url https://tongji.baidu.com/api/manual/Chapter1/overview_getTimeTrendRpt.html
     *! @return Array
     *  
     */
    public function getTimeTrendRpt($query){
        $result = Tools::query([
            'method'  =>  'POST',
            'action'   =>  $this->api.'report/getData',
            'options'  =>  [
                'query'   =>  array_merge([
                    'access_token'  =>  $this->token,
                    // 浏览量(PV),访客数(UV),IP数,跳出率(%),平均访问时长(秒)
                    'metrics'   =>  'pv_count,visitor_count,ip_count,bounce_ratio,avg_visit_time,trans_count',
                    'method'    =>  'overview/getTimeTrendRpt'
                ],$query),
                'timeout'   =>  30
            ]
        ]);

        // chart
        $datasets = [];
        $fields = [
            [
                'name'   => '浏览量(PV)',
                'color'  => '#bf242a'
            ],[
                'name'   => '访客数(UV)',
                'color'  => '#d7a98c'
            ],[
                'name'   => 'IP数',
                'color'  => '#8d6449'
            ],[
                'name'   => '跳出率(%)',
                'color'  => '#ad7d4c'
            ],[
                'name'   => '平均访问时长(秒)',
                'color'  => '#f08300'
            ],[
                'name'   => '转化次数',
                'color'  => '#fcd575'
            ]
        ];

        $items = $result['data']['result']['items'];
        
        foreach ($fields as $k => $v) {
            $datasetsData = [];
            foreach ($items[1] as $value) {
                $datasetsData[] = $value[$k];
            }
        
            $datasets[] = [
                'label'  => $v['name'],
                'backgroundColor' => $v['color'],
                'borderColor'  => $v['color'],
                'data'   => $datasetsData,
                'borderWidth' => 1,
                'fill'   => false
            ]; 
        }

        $result['chart'] = [
            'handle' => [
                'data'   => [
                   'type'   => 'bar',
                   'data'   => [
                      'labels' => $items[0],
                      'datasets'  => $datasets
                   ],
                   'options'   => [
                      'tooltips'  => [
                         'mode'   => 'index',
                         'intersect' => false
                      ],
                      'legend' => [
                         'display'   => true
                      ],
                      'hover'  => [
                         'mode'   => 'nearest',
                         'intersect' => true
                      ],
                      'scales' => [
                         'xAxes'  => [
                            [
                               'display'   => true,
                               'scaleLabel'   => [
                                  'display'   => false,
                                  'labelString'  => 'Month'
                               ]
                            ]
                         ],
                         'yAxes'  => [
                            'display'   => true,
                            'scaleLabel'   => [
                               'display'   => true,
                               'labelString'  => 'Value'
                            ]
                         ]
                      ],
                      'title'  => [
                         'display'   => true,
                         'text'   => '网站概况(趋势数据)'
                      ]
                   ]
                ]
            ]
        ];

        return $result;
	}
    /** 
     ** 网站概况(地域分布)
     *? @date 22/12/23 16:33
     *? @url https://tongji.baidu.com/api/manual/Chapter1/overview_getDistrictRpt.html
     *! @return Array
     */
    public function getDistrictRpt($query){
        $result = Tools::query([
            'method'  =>  'POST',
            'action'   =>  $this->api.'report/getData',
            'options'  =>  [
                'query'   =>  array_merge([
                    'access_token'  =>  $this->token,
                    // 浏览量(PV)
                    'metrics'   =>  'pv_count',
                    'method'    =>  'overview/getDistrictRpt'
                ],$query),
                'timeout'   =>  30
            ]
        ]);

        $area = [];
        $pv = [];
        $proportion = [];

        foreach ($result['data']['result']['items'][0] as $key => $value) {
            $area[] = $value[0];
            $pv[] = $result['data']['result']['items'][1][$key][0];
            $proportion[] = $result['data']['result']['items'][1][$key][1];
        }

        $result['chart'] = [
            'handle' => [
               'data'   => [
                  'type'   => 'line',
                  'data'   => [
                     'labels' => $area,
                     'datasets'  => [
                        [
                           "label"  =>  "浏览量(PV)",
                           'fill'   =>  false,
                           'backgroundColor'    => '#bf242a',
                           'borderColor'  =>  '#bf242a',
                           'borderWidth' => 1,
                           'data'   =>  $pv
                        ],
                        [
                           "label"  =>  "占比(%)",
                           'fill'   =>  false,
                           'backgroundColor'    => '#d7a98c',
                           'borderColor'  =>  '#d7a98c',
                           'borderWidth' => 1,
                           'data'   =>  $proportion
                        ]
                     ]
                  ],
                  'options'   => [
                     'title'  => [
                        'display'   => true,
                        'text'   => '网站概况(地域分布)'
                     ],
                     'legend' => [
                        'display'   => true
                     ],
                     'plugins'   => [
                        'legend' => 'onHover',
                        'onLeave'   => 'handleLeave'
                     ]
                  ]
               ] 
            ]
        ];

        return $result;
	}
    /** 
     ** 网站概况(来源网站、搜索词、入口页面、受访页面)
     *? @date 22/12/23 16:33
     *? @url https://tongji.baidu.com/api/manual/Chapter1/overview_getCommonTrackRpt.html
     *! @return Array
     */
    public function getCommonTrackRpt($query){
        return Tools::query([
            'method'  =>  'POST',
            'action'   =>  $this->api.'report/getData',
            'options'  =>  [
                'query'   =>  array_merge([
                    'access_token'  =>  $this->token,
                    // 浏览量(PV)
                    'metrics'   =>  'pv_count',
                    'method'    =>  'overview/getCommonTrackRpt'
                ],$query),
                'timeout'   =>  30
            ]
        ]);
	}
    /** 
     ** 趋势分析
     *? @date 23/01/01 13:24
     *?  @url https://tongji.baidu.com/api/manual/Chapter1/trend_time_a.html
     *! @return Array
     */
    public function trendTimeA($query){
        return Tools::query([
            'method'  =>  'POST',
            'action'   =>  $this->api.'report/getData',
            'options'  =>  [
                'query'   =>  array_merge([
                    'access_token'  =>  $this->token,
                    // 浏览量(PV)
                    'metrics'   =>  'pv_count,pv_ratio,visit_count,visitor_count,new_visitor_count,new_visitor_ratio,ip_count,avg_visit_time,avg_visit_pages,trans_count,trans_ratio,avg_trans_cost,income',
                    'method'    =>  'trend/time/a'
                ],$query),
                'timeout'   =>  30
            ]
        ]);
	}
    /** 
     ** 实时访客
     *? @date 23/01/01 13:24
     *  @url https://tongji.baidu.com/api/manual/Chapter1/trend_latest_a.html
     *! @return Array
     */
    public function trendLatestA($query){
        return Tools::query([
            'method'  =>  'POST',
            'action'   =>  $this->api.'report/getData',
            'options'  =>  [
                'query'   =>  array_merge([
                    'access_token'  =>  $this->token,
                    // 浏览量(PV)
                    'metrics'   =>  'area,searchword,visit_time,visit_pages',
                    'order' =>  'visit_pages,desc',
                    'source'    =>  'through',
                    'area'  =>  'china',
                    'method'    =>  'trend/latest/a'
                ],$query),
                'timeout'   =>  30
            ]
        ]);
	}
    /** 
     ** 搜索词
     *? @date 23/01/01 16:15
     *?  @url https://tongji.baidu.com/api/manual/Chapter1/source_searchword_a.html
     *! @return Array
     */
    public function sourceSearchwordA($query){
        return Tools::query([
            'method'  =>  'POST',
            'action'   =>  $this->api.'report/getData',
            'options'  =>  [
                'query'   =>  array_merge([
                    'access_token'  =>  $this->token,
                    // 浏览量(PV)
                    'metrics'   =>  'pv_count,visit_count,visitor_count',
                    'method'    =>  'source/searchword/a'
                ],$query),
                'timeout'   =>  30
            ]
        ]);
	}
}