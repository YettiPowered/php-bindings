<?php

namespace Yetti\API\Tests;

/**
 * Test methods for the item filter list model.
 *
 * @author Sam Holman <sam@yetti.co.uk>
 * @copyright Copyright (c) 2011-2012, Yetti Ltd.
 * @package yetti-api
 * @subpackage tests
 */

class Item_Filter_ListTest extends AuthAbstract
{
	public function testInvalidTypeFailsToLoad()
	{
		$list = new \Yetti\API\Item_Filter_List();
		$this->assertFalse($list->load(-1));
	}
	
	public function testValidTypeLoad()
	{
		/**
		 * First need to make a filter type
		 */
		$filterType = new \Yetti\API\Item_Filter_Type();
		$filterType->setItemTypeId(4);
		$filterType->setName('Filter type');
		$this->assertTrue($filterType->save()->success());
		
		/**
		 * And a filter
		 */
		$filter = new \Yetti\API\Item_Filter();
		$filter->setFilterTypeId($filterType->getId());
		$filter->setName('A filter');
		$this->assertTrue($filter->save()->success());
		
		/**
		 * Now check the list
		 */
		$list = new \Yetti\API\Item_Filter_List();
		$this->assertTrue($list->load($filterType->getId()));
		$this->assertCount(1, $list->getItems());
	}
}
