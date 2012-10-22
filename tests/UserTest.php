<?php

namespace Yetti\API\Tests;
use Yetti\API\User as User;

/**
 * Test methods for the user model.
 *
 * @author Sam Holman <sam@yetti.co.uk>
 * @copyright Copyright (c) 2011-2012, Yetti Ltd.
 * @package yetti-api
 * @subpackage tests
 */

class UserTest extends AuthAbstract
{
	/**
	 * @expectedException \Yetti\API\Exception
	 */
	public function testSomething()
	{
		$user = new User();
		$user->save();
	}
	
	public function testFailToSaveInvalidUser()
	{
		$user = new User();
		$this->assertTrue($user->loadTemplate(9999));
		$this->assertFalse($user->save()->success());
	}
	
	public function testSaveValidUser()
	{
		$user = new User();
		$this->assertTrue($user->loadTemplate(1));
		$this->assertFalse($user->save()->success());
		
		$user->setName('Test user ' . microtime(true));
		$this->assertFalse($user->save()->success());

		$user->setPassword('password');
		$this->assertFalse($user->save()->success());
		
		$user->setEmail('test' . microtime(true) . '@example.org');
		$this->assertTrue($user->save()->success());
		
		return $user;
	}
	
	/**
	 * @depends testSaveValidUser
	 */
	public function testLoadUser(User $inUser)
	{
		$user = new User();
		$this->assertFalse($user->load(-1)); // Invalid user
		$this->assertTrue($user->load($inUser->getId()));
		
		$this->assertTrue($user->isLanguageActive());
		$this->assertEquals('test-user', substr($user->getName(), 0, 9));
		$this->assertEquals('test', substr($user->getEmail(), 0, 4));
		$this->assertEquals('@example.org', substr($user->getEmail(), -12));
		$this->assertNotEmpty($user->getPassHash());
		
		return $user;
	}
}
