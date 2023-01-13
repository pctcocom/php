<?php
namespace pctco\php\safety\algorithm;
class AES{
    public function result(bool $event,$data,$token,$salt = null){
        return $event === true?
        $this->encrypt($data, $token, $salt):
        $this->decrypt($data, $token);
    }
    public function encrypt($data, $token, $salt = null) {
        if (is_array($data)) $data = json_encode($data);
        $salt = $salt ?: openssl_random_pseudo_bytes(8);
        list($key, $iv) = $this->evpkdf($token, $salt);
        $ct = openssl_encrypt($data, 'aes-256-cbc', $key, true, $iv);
        return $this->encode($ct, $salt);
    }
    public function decrypt($encryption, $token) {
        list($ct, $salt) = $this->decode($encryption);
        list($key, $iv) = $this->evpkdf($token, $salt);
        $data = openssl_decrypt($ct, 'aes-256-cbc', $key, true, $iv);
        
        // 处理json
        $array = json_decode($data,true);
        return $array === null?$data:$array;
    }		
    
    public function evpkdf($token, $salt) {
        $salted = '';
        $dx = '';
        while (strlen($salted) < 48) {
            $dx = md5($dx . $token . $salt, true);
            $salted .= $dx;
        }
        $key = substr($salted, 0, 32);
        $iv = substr($salted, 32, 16);
        return [$key, $iv];
    }		
    
    public function decode($base64) {
        $data = base64_decode($base64);
        if (substr($data, 0, 8) !== "Salted__") {
            return;
        }
        $salt = substr($data, 8, 8);
        $ct = substr($data, 16);
        return [$ct, $salt];
    }

    public function encode($ct, $salt) {
        return base64_encode("Salted__" . $salt . $ct);
    }
}
