<?php

namespace wbensz\ApiClientLib;

use Nyholm\Psr7\ {Factory, Request, Response, MessageTrait, RequestTrait};

class ApiClient
{
    public static function send(Request $request)
    {
        $response = new Response;
        $body = $request->getBody();
        $method = strtoupper($request->getMethod());
        switch ($method) {
            case 'GET':
                if (empty($body)) {
                    $uri = $request->getUri();
                }
                else {
                    $uri = $request->getUri() . '?' . $body;
                }
                $options = [
                    CURLOPT_HTTPGET => 1,
                    CURLOPT_HEADER => 0,
                    CURLOPT_URL => $uri,
                    CURLOPT_RETURNTRANSFER => TRUE,
                    CURLOPT_TIMEOUT => 4
                ];
                break;
            case 'POST':
                $options = [
                    CURLOPT_POST => 1,
                    CURLOPT_HEADER => 0,
                    CURLOPT_URL => $request->getUri(),
                    CURLOPT_FRESH_CONNECT => 1,
                    CURLOPT_RETURNTRANSFER => TRUE,
                    CURLOPT_FORBID_REUSE => 1,
                    CURLOPT_TIMEOUT => 4,
                    CURLOPT_POSTFIELDS => $body
                ];
                break;
            case 'PUT':
                $options = [
                    CURLOPT_PUT => 1,
                    CURLOPT_HEADER => 0,
                    CURLOPT_URL => $request->getUri(),
                    CURLOPT_FRESH_CONNECT => 1,
                    CURLOPT_RETURNTRANSFER => TRUE,
                    CURLOPT_FORBID_REUSE => 1,
                    CURLOPT_TIMEOUT => 4
                ];
                break;
                default:
                $options = [
                    CURLOPT_CUSTOMREQUEST => $method,
                    CURLOPT_HEADER => 0,
                    CURLOPT_URL => $request->getUri(),
                    CURLOPT_FRESH_CONNECT => 1,
                    CURLOPT_RETURNTRANSFER => TRUE,
                    CURLOPT_FORBID_REUSE => 1,
                    CURLOPT_TIMEOUT => 4
                ];
        }

        $ch = curl_init();
        curl_setopt_array($ch, ($options));
        $curl_result = curl_exec($ch);
        if (FALSE === $curl_result)
        {
            trigger_error(curl_error($ch));
            return $response;
        }
        else {
        curl_close($ch);
        return self::getResponse($response, $curl_result);
        }
    }

    protected static function getResponse(Response $response, $curl_result) {
    //    TODO: fill response object with actual response from cUrl
       return $response;
    }
}
