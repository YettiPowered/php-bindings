<?php

namespace Yetti\API\Tests;

/**
 * Test methods for the item filter type list model.
 *
 * @author Sam Holman <sam@yetti.co.uk>
 * @copyright Copyright (c) 2011-2012, Yetti Ltd.
 * @package yetti-api
 * @subpackage tests
 */

class Item_Filter_Type_ListTest extends AuthAbstract
{
	public function testInvalidTypeFailsToLoad()
	{
		$list = new \Yetti\API\Item_Filter_Type_List();
		$this->assertFalse($list->load(-1));
	}
	
	public function testValidTypeLoad()
	{
		$list = new \Yetti\API\Item_Filter_Type_List();
		$this->assertTrue($list->load(4));
	}
}
