<?php

class AUTHORIZATION
{
    public static function validateTimestamp($token)
    {
        $CI =& get_instance();
        $token = self::validateToken($token);

        //change now() to time() because now() is not working
        if ($token != false && (time() - $token->timestamp < ($CI->config->item('token_timeout') * 60))) {
            return $token;
        }
        return false;
    }

    public static function validateToken($token)
    {
        $CI =& get_instance();
        return JWT::decode($token, $CI->config->item('jwt_key'));
    }

    public static function generateToken($data)
    {
        $CI =& get_instance();
        return JWT::encode($data, $CI->config->item('jwt_key'));
    }

}