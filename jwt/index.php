<?php
/**
 * JSON Web Token
 * irul @20161104
 */

/*
- http://www.christianengvall.se/jwt-json-web-token/
- https://www.sitepoint.com/php-authorization-jwt-json-web-tokens/
 */

$username = '';
$password = '';
$token    = '';

function jwt($data = array(), $secret_key)
{
    $header = json_encode(array(
        "typ" => "JWT",
        "alg" => "HS256",
    ));
    $payload = json_encode($data);

    $encoded_header  = base64_encode($header);
    $encoded_payload = base64_encode($payload);
    $hnp_combined    = $encoded_header . '.' . $encoded_payload;
    $signature       = base64_encode(hash_hmac('sha256', $hnp_combined, $secret_key, true));
    $jwt_token       = $hnp_combined . '.' . $signature;

    return $jwt_token;
}

$headers = apache_request_headers();
if (isset($headers['Authorization'])) {
    $auth = $headers['Authorization']; // Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VybmFtZSI6ImlydWwiLCJwYXNzd29yZCI6ImFzZGFzZCJ9.gjsMxL9PxA+tFNgVrcAyAOgcNTZsCVRWyVQsRFuumZE=
    // echo $auth;
    list($jwt) = sscanf($auth, 'Bearer %s');
    // echo $jwt; //eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VybmFtZSI6ImlydWwiLCJwYXNzd29yZCI6ImFzZGFzZCJ9.gjsMxL9PxA+tFNgVrcAyAOgcNTZsCVRWyVQsRFuumZE=

    if (!$jwt) {
        echo json_encode(array('sukses' => 0, 'msg' => 'Failed'));
        exit;
    }

    $secret_key   = 'secret';
    $recieved_jwt = str_replace(" ", "+", $jwt);
    $jwt_values   = explode('.', $recieved_jwt);

    $recieved_signature          = $jwt_values[2];
    $recieved_header_and_payload = $jwt_values[0] . '.' . $jwt_values[1];

    $what_signature_should_be = base64_encode(hash_hmac('sha256', $recieved_header_and_payload, $secret_key, true));

    // echo json_encode(array('sukses' => 0, 'msg' => $jwt));
    // exit;

    if ($what_signature_should_be == $recieved_signature) {
        echo json_encode(array(
            'sukses'                   => 1,
            'msg'                      => 'Signature Verified',
            'recieved_signature'       => $recieved_signature,
            'what_signature_should_be' => $what_signature_should_be,
        ));
        exit;
    } else {
        echo json_encode(array(
            'sukses'                   => 0,
            'msg'                      => 'Invalid Signature',
            'recieved_signature'       => $recieved_signature,
            'what_signature_should_be' => $what_signature_should_be,
        ));
        exit;
    }
} else {
    if ($_POST) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $tokenId    = base64_encode(mcrypt_create_iv(32));
        $issuedAt   = time();
        $notBefore  = $issuedAt + 10; //Adding 10 seconds
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
                'username' => $username,
                'password' => $password,
            ),
        );

        $key   = 'secret';
        $token = jwt($data, $key);
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>JWT Auth</title>
</head>
<body>
    <h1>JWT Auth</h1>
    <form id="form1" action="" method="post">
        User : <input type="text" name="username" value="<?php echo $username; ?>">
        Password : <input type="text" name="password" value="<?php echo $password; ?>">
        <input type="submit" name="submit" value="Get token">
    </form>

    <h3>Verify Signature</h3>
    <form id="form2" action="" method="post">
        token :<br><textarea id="token" cols="65" rows="5"><?php echo $token; ?></textarea><br>
        <input type="submit" name="submit2" value="Verify">
    </form>
    <p>Or you can debug <a href="https://jwt.io/#debugger" target="_blank">here</a></p>

    <script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>
    <script>
        $(function(){
            $("#form2").submit(function(e){
                var token = $('#token').val();
                // console.log(token);
                $.ajax({
                    url: 'index.php',
                    type: 'GET',
                    dataType: 'json',
                    beforeSend: function(request){
                        request.setRequestHeader('Authorization', 'Bearer ' + token);
                    },
                    success: function(json) {
                        console.log('recieved_signature '+json.recieved_signature);
                        console.log('what_signature_should_be '+json.what_signature_should_be);
                        alert(json.msg);
                    },
                    error: function() {
                        alert('error');
                    }
                });
                e.preventDefault();
            });
        });
    </script>
</body>
</html>
