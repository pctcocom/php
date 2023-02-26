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
	public function findLadderNode(array $array,$node){

		if (is_string($node)) $node = explode('::',$node);

		if (empty($node)) return $this->obj($array);
		foreach ($node as $kn => $vn) {
			unset($node[$kn]);
			if (empty($array[$vn])) return false;
			return $this->findLadderNode($array[$vn],$node);
		}
	}
	public function merge(array $original, array $a, array $b){
		$result = [];
 
		$a_json = json_encode($a);
		$b_json = json_encode($b);
		$o_json = json_encode($original);
 
		if ($a_json === $o_json) {
			return $b;
		} // No changes in A, return B
		if ($b_json === $o_json) {
			return $a;
		} // No changes in B, return A
 
		//When merging numeric-indexed arrays, ignore the indexes and just merge the contents.
		if ($this->isNumeric($a_json) and $this->isNumeric($b_json)) {
			//In the case of confusion, $b wins, including numeric array order
			//Everything in $b, unless it was known in $original and deleted in $a
			foreach ($b as $item) {
				if (! (in_array($item, $original) && !in_array($item, $a))) {
					array_push($result, $item);
				}
			}
 
			//Everything in $a, that's new to BOTH $b and original
			foreach ($a as $item) {
				if (!in_array($item, $original) && !in_array($item, $b)) {
					array_push($result, $item);
				}
			}
		} else {
			/*
			For associative arrays:
			For every thing on A:
			Exists on B, is complex : recursion
			Exists on B, B differs from Original : B
			Exists on B, B is Original : A
			Doesn't exist on B, doesn't exist on Original : A
			Doesn't exist on B, does exist on Original : skip
			For every thing on B:
			Doesn't exist on A or Original : B
			*/
 
 
			foreach ($a as $key => $value) {
 
				// We've had problems in recursion where there's an object in the middle of the tree
				if (isset($original[$key]) && gettype($original[$key]) === 'object') {
					$original[$key] = json_decode(json_encode($original[$key]), true);
				}
				if (isset($a[$key]) && gettype($a[$key]) === 'object') {
					$a[$key] = json_decode(json_encode($a[$key]), true);
				}
				if (isset($b[$key]) && gettype($b[$key]) === 'object') {
					$b[$key] = json_decode(json_encode($b[$key]), true);
				}
 
				//Does it exist on B?
				if (array_key_exists($key, $b)) {
 
					//and is an array (numeric or associative), use recursion
					if (is_array($a[$key]) and is_array($b[$key])) {
						//It could be new on both, or a primitive on original:
						$recur_orig = (array_key_exists($key, $original) and is_array($original[$key])) ? $original[$key] : [];
 
						$result[$key] = $this->merge($recur_orig, $a[$key], $b[$key]);
 
					//Exists on A and B, B the same as origin : use A
					} elseif (array_key_exists($key, $original) and $original[$key] === $b[$key]) {
						$result[$key] = $a[$key];
 
					//Exists on A and B, B differs from Origin (or is new) : B always wins
					} else {
						$result[$key] = $b[$key];
					}
 
 
					//Does not exist on B, does not exist on origin, use A
				} elseif (! array_key_exists($key, $original)) {
					$result[$key] = $a[$key];
 
				//Does not exist on B, did exist on original (deleted) skip
				} else {
				}
			}
 
			//Now find data inserted on $b that $a doesn't know about
			foreach ($b as $key => $value) {
				if (! array_key_exists($key, $original) and !array_key_exists($key, $a)) {
					$result[$key] = $value;
				}
			}
		}
		return $result;
	}
	public function isNumeric($json){
	   if (!is_string($json)) {
		  $json = json_encode($json);
	   }
	   return substr($json, 0, 1) == '[';
	}
}