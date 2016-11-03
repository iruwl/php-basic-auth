<?php
/**
 * PHP Basic Auth
 * irul @20161104
 */

function json_response($message = null, $code = 200)
{
    // clear the old headers
    header_remove();

    // set the actual code
    http_response_code($code);

    // set the header to make sure cache is forced
    // header("Cache-Control: no-transform,public,max-age=300,s-maxage=900");

    // treat this as json
    header('Content-Type: application/json');

    // return the encoded json
    return json_encode(array(
        'success' => $code == 200, // success or not?
        'message' => $message,
    ));
}

$msg = '';
if (!isset($_SERVER['PHP_AUTH_USER'])) {
    $msg = "Auth is not set!";
    echo json_response($msg, 401);
} else {
    $user     = $_SERVER['PHP_AUTH_USER'];
    $password = $_SERVER['PHP_AUTH_PW'];
    $token    = base64_encode("{$user}:{$password}");

    $msg = "Basic Auth\nuser: {$user}\npassword: {$password}\ntoken: {$token}";
    echo json_response($msg, 200);
}

// try with curl
// curl http://irul:ganteng@localhost/php-basic-auth/basic/basic-auth.php
