<?php

namespace wbensz\ApiClientLib;

class JsonHelper
{
    public static function isJson($string) {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }

    public static function decodeJson() {
        $args = func_get_args();
        $response = call_user_func_array('json_decode', $args);

        if ($response === null) {
            $response = $args['0'];
        }
    }

}
