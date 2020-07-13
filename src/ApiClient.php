<?php

namespace wbensz\ApiClientLib;

require_once __DIR__ . '/../vendor/autoload.php';
use Nyholm\Psr7\ {Factory, Request, Response, Stream };

class ApiClient
{
    public static function send(Request $request)
    {
        $method = strtoupper($request->getMethod());
        $headers = $request->getHeaders();
        $body = (string)$request->getBody();

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
                    CURLOPT_HEADER => 1,
                    CURLOPT_URL => $uri,
                    CURLOPT_RETURNTRANSFER => TRUE,
                    CURLOPT_TIMEOUT => 4
                ];
                break;
            case 'POST':
                $options = [
                    CURLOPT_POST => 1,
                    CURLOPT_HEADER => 1,
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
                    CURLOPT_TIMEOUT => 4,
                    CURLOPT_POSTFIELDS => 1
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
                    CURLOPT_TIMEOUT => 4,
                    CURLOPT_POSTFIELDS => $body
                ];
        }

        $ch = curl_init();
        curl_setopt_array($ch, ($options));
        $curl_result = curl_exec($ch);
        if (FALSE === $curl_result)
        {
            trigger_error(curl_error($ch));
            return new Response;
        }
        else {
            $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
            $response_code = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
            curl_close($ch);

            $curl_headers = substr($curl_result, 0, $header_size);
            $headers = HeaderParser::parseResponseHeaders($curl_headers);
            $body = substr($curl_result, $header_size);
            if (JsonHelper::isJson($body)){
                $body = JsonHelper::decodeJson($body);
            }

            return new Response($response_code, $headers, $body);
        }
    }
}
