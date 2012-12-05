<?php

namespace Yetti\API;

/**
 * Auth details model.
 * 
 * @author Sam Holman <sam@yetti.co.uk>
 * @copyright Copyright (c) 2011-2012, Yetti Ltd.
 * @package yetti-api
 */

class Auth_Details extends BaseAbstract
{
	private
	
		/**
		 * Holds info about the authorised user
		 * 
		 * @var \Yetti\API\User
		 */
		$_user;
	
	/**
	 * Load auth details
	 * 
	 * @return bool
	 */
	public function load()
	{
		$this->webservice()->setRequestPath('/auth/details.ws');
		$this->webservice()->setRequestMethod('get');
		
		if ($this->webservice()->makeRequest())
		{
			$this->setJson($this->webservice()->getResponseJsonObject());
			return true;
		}
		
		return false;
	}
	
	/**
	 * Returns an object representing the authenticated user
	 * 
	 * @return \Yetti\API\User
	 */
	public function getUser()
	{
		if (!$this->_user)
		{
			$this->_user = new User();
			$this->_user->load($this->getJson()->user->resourceId);
		}
		
		return $this->_user;
	}
	
	/**
	 * Returns an array of authorised scopes
	 * 
	 * @return array
	 */
	public function getScopes()
	{
		return (array)$this->getJson()->scopes;
	}
}
