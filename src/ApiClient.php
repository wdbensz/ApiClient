<?php

namespace wbensz\ApiClientLib;

require_once __DIR__ .'/../vendor/autoload.php';
use Nyholm\Psr7\ {Factory, Request, Response, MessageTrait, RequestTrait};

class ApiClient
{
    public static function send(Request $request)
    {
        $response = new Response;
        $body = $request->getBody();
        $method = strtoupper($request->getMethod());

        $headers = $request->getHeaders();


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
                    CURLOPT_TIMEOUT => 4
                    // TODO: set body of the request
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
            $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
            $response_code = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
            curl_close($ch);
            return self::getResponse($response, $curl_result, $header_size, $response_code);
        }
    }

    public static function parseResponseHeaders($curl_headers) {
        $headers = array();
        $curl_headers_exploded = explode("\r\n\r\n", $curl_headers);

        // Array count minus 1 is to avoid addition of an empty row
        //because of the additional line break before the body of the response.
        for ($index = 0; $index < count($curl_headers_exploded) - 1; $index++) {
            foreach (explode("\r\n", $curl_headers_exploded[$index]) as $i => $line)
            {
                if ($i === 0)
                    $headers[$index]['http_code'] = $line;
                else
                {
                    list($key, $value) = explode(': ', $line);
                    $headers[$index][$key] = $value;
                }
            }
        }

        return $headers;
    }

    protected static function getResponse(Response $response, $curl_result, $header_size, $response_code) {
        // $curl_info = curl_getinfo($curl_result);
        // $header_size = curl_getinfo($curl_result, CURLINFO_HEADER_SIZE);
        $curl_headers = substr($curl_result, 0, $header_size);
        $headers = self::parseResponseHeaders($curl_headers);

        // TODO: truncate array - remove all 'http_code' entries before looping:
        for ($index = 0; $index < count($headers); $index++) {
            foreach ($headers[$index] as $key => $value) {
                $response = $response->withAddedHeader($key, $value);
            }
        }
        $response = $response->withStatus($response_code);

        $body = $response->getBody();

        $body->write(substr($curl_result, $header_size));

        return $response;
    }

}
