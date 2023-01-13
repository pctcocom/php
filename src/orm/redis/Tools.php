<?php
namespace pctco\php\orm\redis;
use pctco\php\Helper;
class Tools{
    public function __construct(){
        $this->config = Helper::config('get::orm::redis');
        $this->db = [];
        $this->redis = null;
	}
    public function open(string $database = 'main',int $select = 0){
        foreach ($this->config as $k => $v) {
            if ($k === $database) {
                $this->db = $v;
                break;
            }
        }
        if (empty($this->db)) return false;

        $redis = new \Redis();
        $redis->connect($this->db->hostname,$this->db->hostport);
        if (!empty($this->db->password)) $redis->auth($this->db->password);
        $redis->select($select);
        $this->redis = $redis;
        return $this->redis;
	}
}