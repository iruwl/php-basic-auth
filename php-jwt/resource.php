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

define('SECRET_KEY', 'Your-Secret-Key');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $headers = apache_request_headers();
    if (isset($headers['Authorization'])) {
        // Look for the 'authorization' header
        $auth      = $headers['Authorization'];
        list($jwt) = sscanf($auth, 'Bearer %s');

        if ($jwt) {
            try {
                // decode the jwt using the key from config
                $key   = SECRET_KEY;
                $token = JWT::decode($jwt, $key, array('HS512'));

                // return protected asset
                header('Content-type: application/json');
                echo json_encode(array(
                    'nama'       => 'irul',
                    'ketampanan' => 'maksimal',
                ));

            } catch (Exception $e) {
                /*
                 * the token was not able to be decoded.
                 * this is likely because the signature was not able to be verified (tampered token)
                 */
                header('HTTP/1.0 401 Unauthorized');
            }

        } else {
            /*
             * No token was able to be extracted from the authorization header
             */
            header('HTTP/1.0 400 Bad Request');
        }
    } else {
        /*
         * The request lacks the authorization token
         */
        header('HTTP/1.0 400 Bad Request');
        echo 'Token not found in request';
    }
} else {
    header('HTTP/1.0 405 Method Not Allowed');
}
