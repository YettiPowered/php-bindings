<?php

namespace Yetti\API\Tests;

/**
 * Test methods for the item type list model.
 *
 * @author Sam Holman <sam@yetti.co.uk>
 * @copyright Copyright (c) 2011-2012, Yetti Ltd.
 * @package yetti-api
 * @subpackage tests
 */

class Item_Type_ListTest extends AuthAbstract
{
	public function testLoad()
	{
		$list = new \Yetti\API\Item_Type_List();
		$this->assertTrue($list->load());
		
		$this->assertGreaterThan(0, $list->getTotalItemCount());
		
		foreach ($list->getItems() as $itemType) {
			$this->assertInstanceOf('\Yetti\API\Item_Type', $itemType);
		}
	}
}
