<?php

/*

$chat = new InviteDecoder();
echo 'https://vk.me/join/'.$chat->encodeLink($chat->toLong(550445233, 2000000000), 'y4FHhytej7N4rw=='); // компоненты в ссылку
print_r($chat->toComponents($chat->decodeID('AJQ1d7EgzyDLgUeHK16Ps3iv')[0])); // ссылка в компоненты

*/

class InviteDecoder {
	public function toLong($one, $two){ // XXX, 2000000000
		return $one & 0xFFFFFFFF | $two << 32;
	}
	
	public function toComponents($result){ // 8589934592550445233
		return [$result & 0xFFFFFFFF, $result >> 32];
	}
	
	public function decodeID($encoded){ // vk.me/join/*******
		$c = $this->chat_convert($this->base64url_decode($encoded));
		return [unpack("J", $c[0])[1], base64_encode($c[1])];
	}
	
	public function encodeLink($id, $key){ // invite_chat_id, invite_hash
		$c = $this->chat_convert(pack('J', $id));
		return $this->base64url_encode($c[0].base64_decode($key));
	}
	
	private function base64url_encode($data){
		return rtrim(strtr(base64_encode($data), '+', '_'), '=');
	}
	
	private function base64url_decode($data){
		return base64_decode(str_pad(strtr($data, '_', '+'), strlen($data) % 4, '=', STR_PAD_RIGHT));
	}
	
	private function chat_convert($data){
		$s = str_split($data, 4);
		return [strrev($s[0]).strrev($s[1]), substr($data, 8)];
	}
}
