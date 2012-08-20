<?php

namespace Yetti\API\Tests;

/**
 * Test methods for the item filter model.
 *
 * @author Sam Holman <sam@yetti.co.uk>
 * @copyright Copyright (c) 2011-2012, Yetti Ltd.
 * @package yetti-api
 * @subpackage tests
 */

class Item_FilterTest extends AuthAbstract
{
	public function testCreateFilter()
	{
		/**
		 * First need a filter type
		 */
		$type = new \Yetti\API\Item_Filter_Type();
		$type->setItemTypeId(4);
		$type->setName('Colour');
		$this->assertTrue($type->save()->success());
		
		/**
		 * Create the filter
		 */
		$filter = new \Yetti\API\Item_Filter();
		$this->assertFalse($filter->save()->success());
		
		$filter->setFilterTypeId($type->getId());
		$this->assertFalse($filter->save()->success());
		
		$filter->setName('Red');
		$this->assertTrue($filter->save()->success());
		
		return $filter;
	}
	
	/**
	 * @depends testCreateFilter
	 */
	public function testLoad(\Yetti\API\Item_Filter $inFilter)
	{
		$filter = new \Yetti\API\Item_Filter();
		$this->assertFalse($filter->load(-1));
		$this->assertTrue($filter->load($inFilter->getId()));
		$this->assertEquals('Red', $filter->getName());
		
		return $filter;
	}
	
	/**
	 * @depends testLoad
	 */
	public function testUpdate(\Yetti\API\Item_Filter $inFilter)
	{
		$filterId = $inFilter->getId();
		
		$inFilter->setName('Changed');
		$this->assertTrue($inFilter->save()->success());
		
		$filter = new \Yetti\API\Item_Filter();
		$this->assertTrue($filter->load($filterId));
		$this->assertEquals('Changed', $filter->getName());
		
		return $filter;
	}
	
	/**
	 * @depends testUpdate
	 */
	public function testConditionalFilter(\Yetti\API\Item_Filter $inFilter)
	{
		$filter = new \Yetti\API\Item_Filter();
		$filter->setFilterTypeId($inFilter->getFilterTypeId());
		$filter->setName('Conditional filter');
		$filter->addConditionalFilter($inFilter->getId());
		$this->assertTrue($filter->save()->success());
		$filterId = $filter->getId();
		
		$filter = new \Yetti\API\Item_Filter();
		$this->assertTrue($filter->load($filterId));
		
		$conditionalFilters = $filter->getConditionalFilters();
		$this->assertCount(1, $conditionalFilters);
		$this->assertEquals($inFilter->getId(), $conditionalFilters[0]);
		
		return $filter;
	}
	
	/**
	 * @depends testConditionalFilter
	 */
	public function testAssignItems(\Yetti\API\Item_Filter $inFilter)
	{
		/**
		 * Set up an item first...
		 */
		$item = new \Yetti\API\Item();
		$this->assertTrue($item->loadTemplate(4));
		$item->setName('A test item ' . microtime(true));
		$item->setPropertyValue('Name', 'Test item');
		$item->setPropertyValue('Body', 'A test body');
		$this->assertTrue($item->save()->success());
		
		$this->assertCount(0, $inFilter->getItems());
		$inFilter->addItem($item->getId());
		$this->assertCount(1, $inFilter->getItems());
		$this->assertTrue($inFilter->save()->success());
		
		$filter = new \Yetti\API\Item_Filter();
		$this->assertTrue($filter->load($inFilter->getId()));
		$this->assertCount(1, $filter->getItems());
		
		return $filter;
	}
	
	/**
	 * @depends testAssignItems
	 */
	public function testDelete(\Yetti\API\Item_Filter $inFilter)
	{
		$filterId = $inFilter->getId();
		
		$filter = new \Yetti\API\Item_Filter();
		$this->assertTrue($filter->load($filterId));
		
		$this->assertTrue($filter->delete()->success());
		
		$filter = new \Yetti\API\Item_Filter();
		$this->assertFalse($filter->load($filterId));
	}
}
