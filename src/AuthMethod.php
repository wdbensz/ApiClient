<?php

namespace wbensz\ApiClientLib;

require_once __DIR__ . '/../vendor/autoload.php';

class AuthMethod
{ 
  public function __construct(Authenticator $strategy) 
  { 
    $this->strategy = $strategy; 
  } 
 
  public function addHeader()
  { 
    return $this->strategy->addHeader();
  } 
} 

