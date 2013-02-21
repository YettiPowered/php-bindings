<?php

namespace Yetti\API\Tests;
use Yetti\API\User, Yetti\API\User_Address;

/**
 * Test methods for the user address model.
 *
 * @author Sam Holman <sam@yetti.co.uk>
 * @copyright Copyright (c) 2011-2013, Yetti Ltd.
 * @package yetti-api
 * @subpackage tests
 */

class User_AddressTest extends AuthAbstract
{
	public function testNewAddress()
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
		
		$address = new User_Address();
		$this->assertFalse($address->save()->success());
		
		$address->setUserId($user->getId());
		$address->setIdentifier('Test address');
		$address->setFirstname('Mike');
		$address->setSurname('Moo');
		$address->setLine1('1 Test Street');
		$address->setLine2('Wibble');
		$address->setCity('Leeds');
		$address->setRegion('West Yorkshire');
		$address->setPostcode('LS1 1AB');
		$address->setTelephone('01234 567890');
		$address->setCountryId(233); // United Kingdom
		
		$this->assertTrue($address->save()->success());
		return $address;
	}
	
	/**
	 * @depends testNewAddress
	 */
	public function testLoadAddress(User_Address $inAddress)
	{
		$address = new User_Address();
		$this->assertFalse($address->load(-1, -1));
		$this->assertTrue($address->load($inAddress->getUserId(), $inAddress->getId()));
		
		$this->assertEquals('Test address', $address->getIdentifier());
		$this->assertEquals('Mike', $address->getFirstname());
		$this->assertEquals('Moo', $address->getSurname());
		$this->assertEquals('1 Test Street', $address->getLine1());
		$this->assertEquals('Wibble', $address->getLine2());
		$this->assertEquals('Leeds', $address->getCity());
		$this->assertEquals('West Yorkshire', $address->getRegion());
		$this->assertEquals('LS1 1AB', $address->getPostcode());
		$this->assertEquals('01234 567890', $address->getTelephone());
		$this->assertEquals('United Kingdom', $address->getCountryName());
		$this->assertEquals(233, $address->getCountryId());
		
		return $address;
	}
	
	/**
	 * @depends testLoadAddress
	 */
	public function testUpdateAddress(User_Address $inAddress)
	{
		$inAddress->setIdentifier('Changed address');
		$this->assertTrue($inAddress->save()->success());
		
		$address = new User_Address();
		$this->assertTrue($address->load($inAddress->getUserId(), $inAddress->getId()));
		$this->assertEquals('Changed address', $address->getIdentifier());
	}
}
