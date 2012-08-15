<?php

namespace Yetti\API;

/**
 * User list model
 *
 * @author Sam Holman <sam@yetti.co.uk>
 * @copyright Copyright (c) 2011-2012, Yetti Ltd.
 * @package yetti-api
 */

class User_List extends ListAbstract
{
	/**
	 * Construct a new user list model
	 * 
	 * @return void
	 */
	public function __construct()
	{
		$this->setPath('users');
	}
	
	/**
	 * Returns a new user object
	 * 
	 * @return object \Yetti\API\User
	 */
	protected function getNewItemObject()
	{
		return new \Yetti\API\User();
	}
}
