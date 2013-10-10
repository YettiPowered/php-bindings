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
		 * First need to make a couple of filter types
		 */
		$filterType1 = new \Yetti\API\Item_Filter_Type();
		$filterType1->setItemTypeId(4);
		$filterType1->setName('Filter type 1');
		$this->assertTrue($filterType1->save()->success());
		
		$filterType2 = new \Yetti\API\Item_Filter_Type();
		$filterType2->setItemTypeId(4);
		$filterType2->setName('Filter type 2');
		$this->assertTrue($filterType2->save()->success());
		
		/**
		 * Make an item 
		 */
		$item = new \Yetti\API\Item();
		$this->assertTrue($item->loadTemplate(4));
		$item->setName('A test item ' . microtime(true)); // Name (identifier) has to be unique
		$item->setPropertyValue('Name', 'Test item');
		$item->setPropertyValue('Body', 'A test body');
		$this->assertTrue($item->save()->success());
		
		/**
		 * And two filters
		 */
		$filter1 = new \Yetti\API\Item_Filter();
		$filter1->setFilterTypeId($filterType1->getId());
		$filter1->setName('A filter');
		$filter1->addItem($item->getId());
		$this->assertTrue($filter1->save()->success());
		
		$filter2 = new \Yetti\API\Item_Filter();
		$filter2->setFilterTypeId($filterType2->getId());
		$filter2->setName('A filter');
		$filter2->addItem($item->getId());
		$this->assertTrue($filter2->save()->success());
		
		/**
		 * Now check a list
		 */
		$list = new \Yetti\API\Item_Filter_List();
		$this->assertTrue($list->load($filterType1->getId()));
		$this->assertCount(1, $list->getItems());
		
		return array($filter1, $filter2);
	}
	
	/**
	 * @depends testValidTypeLoad
	 */
	public function testLoadByItemId(array $inFilters)
	{
		$inFilter1 = $inFilters[0];
		$inFilter2 = $inFilters[1];
		
		$items = $inFilter1->getItems();
		
		$item = new \Yetti\API\Item();
		$this->assertTrue($item->load($items[0]));
		
		$filters = new \Yetti\API\Item_Filter_List();
		$this->assertTrue($filters->loadByItemId($item->getId()));
		
		$this->assertInstanceOf('\Yetti\API\Item_Filter_List', $filters);
		$this->assertEquals($inFilter1->getId(), $filters->getItems()->first()->getId());
		
		$filters = new \Yetti\API\Item_Filter_List();
		$this->assertTrue($filters->loadByItemId($item->getId(), $inFilter1->getFilterTypeId()));
		$this->assertEquals($inFilter1->getId(), $filters->getItems()->first()->getId());
		
		$filters = new \Yetti\API\Item_Filter_List();
		$this->assertFalse($filters->loadByItemId(-1));
		
		return $inFilters;
	}
	
	/**
	 * @depends testLoadByItemId
	 */
	public function testClearFiltersByItemId(array $inFilters)
	{
		$inFilter1 = $inFilters[0];
		$inFilter2 = $inFilters[1];
		$items     = $inFilter1->getItems();
		$itemId    = $items[0];
		
		$filters = new \Yetti\API\Item_Filter_List();
		$filters->loadByItemId($itemId);
		$this->assertNotEmpty($filters->getItems());
		
		$filters->clearFiltersForItem($itemId, $inFilter1->getFilterTypeId());
		
		$filters = new \Yetti\API\Item_Filter_List();
		$filters->loadByItemId($itemId, $inFilter1->getFilterTypeId());
		$this->assertEmpty($filters->getItems());
		
		$filters = new \Yetti\API\Item_Filter_List();
		$filters->loadByItemId($itemId, $inFilter2->getFilterTypeId());
		$this->assertNotEmpty($filters->getItems());
		
		$filters->clearFiltersForItem($itemId);
		
		$filters = new \Yetti\API\Item_Filter_List();
		$filters->loadByItemId($itemId);
		$this->assertEmpty($filters->getItems());
	}
}
