<?php
/**
 * PHP JWT Auth
 * irul @20161104
 */

/*
https://www.sitepoint.com/php-authorization-jwt-json-web-tokens/
 */

require_once '../vendor/autoload.php';
use \Firebase\JWT\JWT;

date_default_timezone_set(date_default_timezone_get());
define('SECRET_KEY', 'Your-Secret-Key');

if ($_POST) {
    $tokenId    = base64_encode(mcrypt_create_iv(32));
    $issuedAt   = time();
    $notBefore  = $issuedAt + 1; //Adding 1 seconds
    $expire     = $notBefore + 60; // Adding 60 seconds
    $serverName = 'http://localhost'; // Retrieve the server name from config file

    // Create the token as an array
    $data = array(
        'iat'  => $issuedAt, // Issued at: time when the token was generated
        'jti'  => $tokenId, // Json Token Id: an unique identifier for the token
        'iss'  => $serverName, // Issuer
        'nbf'  => $notBefore, // Not before
        'exp'  => $expire, // Expire
        'data' => array(
            'username' => $_POST['username'],
            'password' => $_POST['password'],
        ),
    );

    /// Here we will transform this array into JWT:
    $key = SECRET_KEY;
    $jwt = JWT::encode(
        $data, //Data to be encoded in the JWT
        $key, // The signing key
        'HS512' // Algorithm used to sign the token, see https://tools.ietf.org/html/draft-ietf-jose-json-web-algorithms-40#section-3
    );
    echo $jwt;

} else {
    echo 'xxx';
}
;
