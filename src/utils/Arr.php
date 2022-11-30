<?php
namespace pctco\php\utils;
class Arr {
    public function obj(array $array, bool $recursive = true){
		$obj = new static;
		foreach ($array as $key => $value) {
			$obj->$key = $recursive && is_array($value)
				? $this->obj($value, true)
				: $value;
		}
		return $obj;
	}
    public function contains(array $array, $value): bool {
		return in_array($value, $array, true);
	}
}