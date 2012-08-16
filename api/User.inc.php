<?php

namespace Yetti\API;

/**
 * Individual user model
 * 
 * @author Sam Holman <sam@yetti.co.uk>
 * @copyright Copyright (c) 2011-2012, Yetti Ltd.
 * @package yetti-api
 */

class User extends Resource_BaseAbstract
{
	/**
	 * Returns a singular name for this type of resource
	 * 
	 * @return string
	 */
	protected function getSingularName()
	{
		return 'user';
	}
	
	/**
	 * Returns the hashed password for this user
	 * 
	 * @return string
	 */
	public function getPassHash()
	{
		return (string)$this->getJson()->item->resource->password;
	}
	
	/**
	* Returns the email address for this user
	*
	* @return string
	*/
	public function getEmail()
	{
		return (string)$this->getJson()->item->resource->email;
	}
}
