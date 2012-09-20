<?php

namespace Yetti\API\Tests;

/**
 * Test methods for item category assignment
 *
 * @author Sam Holman <sam@yetti.co.uk>
 * @copyright Copyright (c) 2011-2012, Yetti Ltd.
 * @package yetti-api
 * @subpackage tests
 */

class AuthAbstract extends \PHPUnit_Framework_TestCase
{
	/**
	 * Set up access credentials for these tests
	 * 
	 * @return void
	 */
	protected function setUp()
	{
		\Yetti\API\Webservice::setDefaultBaseUri(YETTI_API_BASE_URI);
		\Yetti\API\Webservice::setDefaultAccessKey(YETTI_API_ACCESS_KEY);
		\Yetti\API\Webservice::setDefaultPrivateKey(YETTI_API_PRIVATE_KEY);
	}
}
