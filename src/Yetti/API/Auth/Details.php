<?php

namespace Yetti\API;

/**
 * Auth details model.
 * 
 * @author Sam Holman <sam@yetti.co.uk>
 * @copyright Copyright (c) 2011-2013, Yetti Ltd.
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
			$this->_user->load($this->getJson()->user->id);
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


	/**
	 * Return the user's ID
	 *
	 * @return integer
	 */
	public function getUserId()
	{
		return (int)$this->getJson()->user->id;
	}

	/**
	 * Return the user's display name
	 *
	 * @return string
	 */
	public function getDisplayName()
	{
		return (string)$this->getJson()->user->name;
	}

	/**
	 * Return the user's email
	 *
	 * @return string
	 */
	public function getEmail()
	{
		return (string)$this->getJson()->user->email;
	}

	/**
	 * Return the user's gravatar url
	 * 
	 * @return string
	 */
	public function getGravatar()
	{
		return (string)$this->getJson()->user->gravatarUrl;
	}

	/**
	 * Return the user's name
	 * 
	 * @return string
	 */
	public function getName()
	{
		return (string)$this->getJson()->user->username;
	}

	/**
	 * Return the user's identifier (same as name)
	 * 
	 * @return string
	 */
	public function getIdentifier()
	{
		return (string)$this->getName();
	}
	
	/**
	 * Returns an object containing the authorised user's subscription details
	 * 
	 * @return stdClass
	 */
	public function getSubscriptionDetails()
	{
		return $this->getJson()->user->subscription;
	}
}
