<?php

namespace wbensz\ApiClientLib;

require_once __DIR__ . '/../vendor/autoload.php';

class None implements Authenticator
{
    public function addHeader() {
        return null;
    }
}

