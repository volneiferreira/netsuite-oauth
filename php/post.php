<?php
require_once 'OAuth.php';

// Request body
$body->message = 'hello!';

// Generated information
$host = ""; // Restlet url host e.g.: 3612936.restlets.api.netsuite.com
$restlet_url = "";
$account_number = "";
$consumer_key = "";
$consumer_secret = "";
$token_key = "";
$token_secret = "";

// Build OAuth information
$consumer = new OAuthConsumer($consumer_key, $consumer_secret);
$token = new OAuthToken($token_key, $token_secret);
$sig = new OAuthSignatureMethod_HMAC_SHA1();

function generateRandomString()
{
    $length = 20;
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0;$i < $length;$i++)
    {
        $randomString .= $characters[rand(0, $charactersLength - 1) ];
    }
    return $randomString;
}

$params = array(
    'oauth_nonce' => generateRandomString() ,
    'oauth_timestamp' => idate('U') ,
    'oauth_version' => '1.0',
    'oauth_token' => $token_key,
    'oauth_consumer_key' => $consumer_key,
    'oauth_signature_method' => $sig->get_name()
);

$method = "POST";
$req = new OAuthRequest($method, $restlet_url, $params);
$req->set_parameter('oauth_signature', $req->build_signature($sig, $consumer, $token));
$req->set_parameter('realm', $account_number);

$header = array(
    'http' => array(
        'method' => $method,
        'header' => $req->to_header() . ',realm="' . $account_number . '"' . " \r\n" . "Host: " . $host . " \r\n" . "Content-Type: application/json",
        'content' => json_encode($body),
    )
);

var_dump($header);

$context = stream_context_create($header);
$fp = fopen($restlet_url, 'r', false, $context);
fpassthru($fp);

fclose($fp);
?>
