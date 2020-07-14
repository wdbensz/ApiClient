<?php

require_once __DIR__ . '/../vendor/autoload.php';
use wbensz\ApiClientLib\{ApiClient, AuthMethod, Basic, Jwt};
use Nyholm\Psr7\ {Factory, Response};

$psr17Factory = new Factory\Psr17Factory();

$body = [
    'name' => 'Jan',
    'country_id' => 'PL'
];
$query = http_build_query($body);

// easiest workaround to get proper Stream object intended for 'withBody' method:
$responseInstance = new Response(200, [], $query);
$bodyStream = $responseInstance->getBody();

// agify.io provides probable age of a person based on their name and country of origin
$request = $psr17Factory->createRequest('GET', 'https://api.agify.io/');
$request = $request->withBody($bodyStream);

$ApiResponse = ApiClient::send($request);
