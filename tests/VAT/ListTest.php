<?php

namespace Yetti\API\Tests;
use Yetti\API\VAT_List;

/**
 * Test methods for the VAT band list model.
 *
 * @author Sam Holman <sam@yetti.co.uk>
 * @copyright Copyright (c) 2011-2012, Yetti Ltd.
 * @package yetti-api
 * @subpackage tests
 */

class VAT_ListTest extends AuthAbstract
{
	public function testThereAreSomeVATBands()
	{
		$vatList = new VAT_List();
		$this->assertTrue($vatList->load());
		
		$this->assertNotEmpty($vatList->getItems());
	}
}
