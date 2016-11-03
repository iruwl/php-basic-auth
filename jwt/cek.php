<?php
/**
 * JSON Web Token
 * irul @20161104
 */

/*
- http://www.christianengvall.se/jwt-json-web-token/
- https://www.sitepoint.com/php-authorization-jwt-json-web-tokens/
 */

#recieved_jwt would in real life be populated from a $_POST['values'] but for this example this will work
#$recieved_jwt = 'eyJhbGciOiAiSFMyNTYiLCJ0eXAiOiAiSldUIn0=.eyJzY29yZSI6ICIxMiIsIm5hbWUiOiAiQ3JpbGxlIn0=.Or5OIu2KjpE6/rQeg0yDyAjBX7KGlHqRJBBTZYgi3I0=';

$get          = $_GET['token'];
$recieved_jwt = str_replace(" ", "+", $get);

$secret_key = 'thisismysecretkey';

$jwt_values = explode('.', $recieved_jwt);

print_r($recieved_jwt . "<br>");
print_r($jwt_values);
echo "<br>";

$recieved_signature          = $jwt_values[2];
$recieved_header_and_payload = $jwt_values[0] . '.' . $jwt_values[1];

$what_signature_should_be = base64_encode(hash_hmac('sha256', $recieved_header_and_payload, $secret_key, true));
echo $what_signature_should_be;
echo "<br>";

if ($what_signature_should_be == $recieved_signature) {
    // signature is ok, the payload has not been tampered with
    echo "cocok";
} else {
    echo "beda coi";
}
