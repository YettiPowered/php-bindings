<?php

namespace Yetti\API\Tests;
use Yetti\API\Auth_Details;

/**
 * Test methods for the fetching auth details
 *
 * @author Sam Holman <sam@yetti.co.uk>
 * @copyright Copyright (c) 2011-2012, Yetti Ltd.
 * @package yetti-api
 * @subpackage tests
 */

class DetailsTest extends AuthAbstract
{
	public function testLoad()
	{
		$details = new Auth_Details();
		$this->assertTrue($details->load());
		
		$this->assertEquals(YETTI_API_ACCESS_KEY, $details->getUser()->getName());
		$this->assertEmpty($details->getScopes());
	}
}
