<?php

namespace wbensz\ApiClientLib;

require_once __DIR__ . '/../vendor/autoload.php';

interface Authenticator
{
    public function addHeader();
}
