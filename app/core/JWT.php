<?php

namespace core;

class JWT
{
	const KEY = 'foo';
	const HEADER = [
		'typ' => 'JWT',
		'alg' => 'HS256'
	];

	public static function generate($payload){
		$header = base64_encode(json_encode(static::HEADER));
		$payload = base64_encode(json_encode($payload));
		$signature = hash_hmac('sha256', $header . '.'.$payload, static::KEY);
		return $header . '.' . $payload . '.' . $signature;
	}

	
}