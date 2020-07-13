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

// example of a JWT from jwt.io
$token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQ.SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c';
$Authenticator = new AuthMethod(new Jwt($token));

$ApiResponse = ApiClient::send($request);
