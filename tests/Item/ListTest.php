<?php

namespace Yetti\API\Tests;

/**
 * Test methods for the item list model.
 *
 * @author Sam Holman <sam@yetti.co.uk>
 * @copyright Copyright (c) 2011-2012, Yetti Ltd.
 * @package yetti-api
 * @subpackage tests
 */

class Item_ListTest extends AuthAbstract
{
	public function testInvalidTypeFailsToLoad()
	{
		$list = new \Yetti\API\Item_List();
		$this->assertFalse($list->load(-1));
	}
	
	public function testValidTypeLoad()
	{
		$list = new \Yetti\API\Item_List();
		$this->assertTrue($list->load(4));
		
		$this->assertGreaterThan(0, $list->getTotalItemCount());
	}
}
