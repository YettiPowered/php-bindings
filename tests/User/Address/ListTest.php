<?php

namespace Yetti\API\Tests;
use Yetti\API\User, Yetti\API\User_Address_List;

/**
 * Test methods for the user address list model.
 *
 * @author Sam Holman <sam@yetti.co.uk>
 * @copyright Copyright (c) 2011-2013, Yetti Ltd.
 * @package yetti-api
 * @subpackage tests
 */

class User_Address_ListTest extends AuthAbstract
{
	public function testNewUserHasNoAddresses()
	{
		/**
		 * Need a user first...
		 */
		$user = new User();
		$user->loadTemplate(1);
		$user->setName('Test user ' . microtime(true));
		$user->setPassword('password');
		$user->setEmail('test' . microtime(true) . '@example.org');
		$this->assertTrue($user->save()->success());
		
		$addressList = new User_Address_List();
		$this->assertFalse($addressList->load(-1));
		$this->assertTrue($addressList->load($user->getId()));
		
		$this->assertEmpty($addressList->getItems());
	}
}
