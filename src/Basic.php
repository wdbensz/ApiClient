<?php

namespace wbensz\ApiClientLib;

require_once __DIR__ . '/../vendor/autoload.php';

class Basic implements Authenticator
{
    protected $user;
    protected $password;


    public function addHeader() {
        $credentials = $this->user . ':' . $this->password;
        return 'Authorization: Basic ' . base64_encode($credentials);
    }

	function getUser() {
		return $this->user;
	}
	
	function setUser($user): self {
		$this->user = $user;
		return $this;
	}

	function getPassword() {
		return $this->password;
	}
	
	function setPassword($password): self {
		$this->password = $password;
		return $this;
    }

}
