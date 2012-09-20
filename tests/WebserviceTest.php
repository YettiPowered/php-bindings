<?php

namespace Yetti\API\Tests;

/**
 * Test methods for the main Webservice class.
 *
 * @author Sam Holman <sam@yetti.co.uk>
 * @copyright Copyright (c) 2011-2012, Yetti Ltd.
 * @package yetti-api
 * @subpackage tests
 */

class WebserviceTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @expectedException \Yetti\API\Exception
	 */
	public function testExceptionWhenUriNotSet()
	{
		$webservice = new \Yetti\API\Webservice();
		$webservice->makeRequest();
	}
	
	public function testInvalidRequestPath()
	{
		$webservice = new \Yetti\API\Webservice();
		$webservice->setBaseUri('http://yetti.co.uk');
		$webservice->setRequestPath('/invalid.ws');
		$this->assertFalse($webservice->makeRequest());
	}
	
	public function testValidRequestPath()
	{
		$webservice = new \Yetti\API\Webservice();
		$webservice->setBaseUri('http://yetti.co.uk');
		$webservice->setRequestPath('/sample/test.ws');
		$this->assertTrue($webservice->makeRequest());
	}
	
	/**
	 * @expectedException \Yetti\API\Exception_Auth
	 */
	public function testThatBadAuthFails()
	{
		$webservice = new \Yetti\API\Webservice();
		$webservice->setBaseUri('http://yetti.co.uk');
		$webservice->setRequestPath('/items.ws');
		$webservice->makeRequest();
	}
	
	public function testThatAuthSucceeds()
	{
		$webservice = new \Yetti\API\Webservice();
		$webservice->setBaseUri(YETTI_API_BASE_URI);
		$webservice->setRequestPath('/items.ws');
		$webservice->setAccessKey(YETTI_API_ACCESS_KEY);
		$webservice->setPrivateKey(YETTI_API_PRIVATE_KEY);
		
		try {
			$this->assertTrue($webservice->makeRequest());
		}
		catch (\Yetti\API\Exception $e) {
			$this->fail('Auth failed. Have you configured your site settings in Config.php?');
		}
	}
}
