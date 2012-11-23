<?php

namespace Yetti\API;

/**
 * Oauth access token model.
 * 
 * @author Sam Holman <sam@yetti.co.uk>
 * @copyright Copyright (c) 2011-2012, Yetti Ltd.
 * @package yetti-api
 */

class Oauth_Access_Token extends BaseAbstract
{
	private
		/**
		 * The values to send when requesting a token
		 * 
		 * @var array
		 */
		$_values = array(
			'client_id' 	=> null,
			'client_secret' => null,
			'code' 			=> null,
			'state' 		=> null,
		);
	
	/**
	 * Set the client ID
	 * 
	 * @param string $clientId
	 * @return void
	 */
	public function setClientId($clientId)
	{
		if (is_string($clientId)) {
			$this->_values['client_id'] = $clientId;
		}
	}
	
	/**
	 * Set the client secret
	 * 
	 * @param string $secret
	 * @return void
	 */
	public function setClientSecret($secret)
	{
		if (is_string($secret)) {
			$this->_values['client_secret'] = $secret;
		}
	}
	
	/**
	 * Set the code
	 * 
	 * @param string $code
	 * @return void
	 */
	public function setCode($code)
	{
		if (is_string($code)) {
			$this->_values['code'] = $code;
		}
	}
	
	/**
	 * Set the state
	 * 
	 * @param string $state
	 * @return void
	 */
	public function setState($state)
	{
		if (is_string($state)) {
			$this->_values['state'] = $state;
		}
	}
	
	/**
	 * Returns an access token
	 * 
	 * @return string
	 */
	public function getAccessToken()
	{
		if ($this->makeRequest())
		{
			if ($token = isset($this->getJson()->access_token) ? $this->getJson()->access_token : null) {
				return $token;
			}
		}
	}
	
	/**
	 * Make a webservice request
	 * 
	 * @return bool
	 */
	private function makeRequest()
	{
		$this->webservice()->setRequestPath('/oauth/access_token.ws');
		$this->webservice()->setRequestMethod('post');
		$this->webservice()->setPostData(json_encode($this->_values));
		
		if ($this->webservice()->makeRequest())
		{
			$this->setJson($this->webservice()->getResponseJsonObject());
			return true;
		}
		
		return false;
	}
}
