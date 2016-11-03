<?php
/**
 * JSON Web Token
 * irul @20161104
 */

/*
- http://www.christianengvall.se/jwt-json-web-token/
- https://www.sitepoint.com/php-authorization-jwt-json-web-tokens/
 */

$encoded_header  = base64_encode('{"alg": "HS256","typ": "JWT"}');
$encoded_payload = base64_encode('{"score": "12","name": "Crilley"}');

$header_and_payload_combined = $encoded_header . '.' . $encoded_payload;

$secret_key = 'thisismysecretkey';
$signature  = base64_encode(hash_hmac('sha256', $header_and_payload_combined, $secret_key, true));
$jwt_token  = $header_and_payload_combined . '.' . $signature;

echo $jwt_token;