<?php
/**
 * PHP Basic Auth
 * irul @20161104
 */

function test($username, $password)
{
    // alternate method without CURLOPT_USERPWD
    $headers1 = array(
        'Content-Type:application/json',
        'Authorization: Basic ' . base64_encode("{$username}:{$password}"),
    );

    $headers2 = array(
        'Content-Type:application/json',
    );
    $host = 'http://localhost/php-basic-auth/basic/basic-auth.php';
    $post = array();

    $ch = curl_init($host);
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers2);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($ch);
    curl_close($ch);

    return $response;
    /*
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
 */
}

header('Content-Type: text/plain');
// header('Content-Type: application/json');
echo test('irul', 'ganteng');
