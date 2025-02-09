<?php
function checkAuth()
{
    if (isset($_COOKIE['auth_token'])) {
        $tokenData = json_decode(base64_decode($_COOKIE['auth_token']), true);
        if (is_array($tokenData) && isset($tokenData['user_id'])) {
            return $tokenData;
        }
    }
    return null;
}

// Check if user is logged in
$authUser = checkAuth();
