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
}
