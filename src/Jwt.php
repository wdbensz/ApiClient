<?php

namespace wbensz\ApiClientLib;

require_once __DIR__ . '/../vendor/autoload.php';

class Jwt implements Authenticator
{
    protected $token;
    
    public function __construct($token = null) {
        if ($token) {
            $this->token = $token;
        }
    }

    public function setToken($token): self {
        $this->token = $token;
        return $this;
    }

    public function getToken() {
        return $this->token;
    }

    public function addHeader() {
        return 'Authorization: Bearer' . ' ' . $this->token;
    }
}
