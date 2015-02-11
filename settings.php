<?php

class Settings{

	private $api_key;
	/**
	 * @return mixed
	 */
	public function getApiKey(){
		return $this->api_key;
	}

	private $api_secret;
	/**
	 * @return mixed
	 */
	public function getApiSecret(){
		return $this->api_secret;
	}

	private $return_url;
	/**
	 * @return mixed
	 */
	public function getReturnUrl(){
		return $this->return_url;
	}

	private $tag;
	/**
	 * @return mixed
	 */
	public function getTag(){
		return $this->tag;
	}

	private $round;
	/**
	 * @return mixed
	 */
	public function getRound(){
		return $this->round;
	}

	function __construct(){
		$this->api_key = '';
		$this->api_secret = '';
		$this->return_url = 'http://localhost/Wodejmowanie';

		$this->tag = 'odejmowanie';
		$this->round = true;
	}

}
