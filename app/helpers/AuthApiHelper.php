<?php

require_once("config.php");

function base64url_encode($data) {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

class AuthApiHelper {

    function getAuthHeaders() {
        $header = "";
        // y si setearon las 2? siempre te quedas con la 2da, perdes la 1ra...
        if(isset($_SERVER['HTTP_AUTHORIZATION']))
            $header = $_SERVER['HTTP_AUTHORIZATION'];
        if(isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION']))
            $header = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
        return $header;
    }

    function createToken($payload){
        $header = array(
            'alg' => 'HS256',
            'typ' => 'JWT'
        );
        
        $header = base64url_encode(json_encode($header));
        $payload = base64url_encode(json_encode($payload));
        $signature = hash_hmac('SHA256', "$header.$payload", JWT_KEY, true);
        $signature = base64url_encode($signature);

        $token = "$header.$payload.$signature";
        
        return $token;
    }

    function verify($token) {
        //$header.$payload.$signature

        $token = explode(".", $token); // [$header , $payload , $signature]
        $header = $token[0];
        $payload = $token[1];
        $signature = $token[2];

        $new_signature = hash_hmac('SHA256', "$header.$payload", JWT_KEY, true);
        $new_signature = base64url_encode($new_signature);

        if($signature!=$new_signature){
            return false;
        }

        $payload = json_decode(base64_decode($payload));

        return $payload;
    }

}