<?php

class HumanResponse {
	private $message;
	private $code;
	
	function __construct() {
		$this->message = "";
		$this->code = "";
	}
	
	public function setCode($code) {
		$this->code = $code;
	}
	
	public function setMessage($message) {
		$this->message = $message;
	}
	
	public function getCode() {
		return $this->code;
	}
	
	public function getMessage() {
		return $this->message;
	}
}