<?php

namespace Yetti\API\Tests;

/**
 * Test methods for the item type model.
 *
 * @author Sam Holman <sam@yetti.co.uk>
 * @copyright Copyright (c) 2011-2012, Yetti Ltd.
 * @package yetti-api
 * @subpackage tests
 */

class Item_TypeTest extends AuthAbstract
{
	public function testInvalidTypeFailsToLoad()
	{
		$itemType = new \Yetti\API\Item_Type();
		$this->assertFalse($itemType->load(-1));
	}
	
	public function testValidItemTypeLoad()
	{
		$itemType = new \Yetti\API\Item_Type();
		$this->assertTrue($itemType->load(4));
		
		$this->assertEquals(4, $itemType->getId());
	}
}
