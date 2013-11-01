<?php

namespace Yetti\API\Tests;

/**
 * Test methods for the user type model.
 *
 * @author Sam Holman <sam@yetti.co.uk>
 * @copyright Copyright (c) 2011-2012, Yetti Ltd.
 * @package yetti-api
 * @subpackage tests
 */

class User_TypeTest extends AuthAbstract
{
	public function testInvalidTypeFailsToLoad()
	{
		$userType = new \Yetti\API\User_Type();
		$this->assertFalse($userType->load(-1));
	}
	
	public function testValidUserTypeLoad()
	{
		$userType = new \Yetti\API\User_Type();
		$this->assertTrue($userType->load(1));
		
		$this->assertEquals(1, $userType->getId());
		$this->assertEquals('Customer', $userType->getName());
	}
}
