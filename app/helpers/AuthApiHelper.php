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
    
    











    }