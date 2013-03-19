<?php

namespace Yetti\API\Tests;

/**
 * Test methods for the collection filter list model.
 *
 * @author Sam Holman <sam@yetti.co.uk>
 * @copyright Copyright (c) 2011-2013, Yetti Ltd.
 * @package yetti-api
 * @subpackage tests
 */

class Collection_Filter_ListTest extends AuthAbstract
{
	public function testInvalidCollectionFailsToLoad()
	{
		$list = new \Yetti\API\Collection_Filter_List();
		$this->assertFalse($list->load(-1));
	}
	
	public function testValidCollectionLoad()
	{
		$collection = new \Yetti\API\Collection();
		$collection->loadTemplate(4);
		$collection->setIdentifier('A collection with filters ' . microtime(true));
		$collection->setPropertyValue('Name', 'A collection with filters');
		$this->assertTrue($collection->save()->success());
		
		/**
		 * First need to make a filter type
		 */
		$filterType = new \Yetti\API\Collection_Filter_Type();
		$filterType->setCollectionId($collection->getId());
		$filterType->setName('Filter type');
		$this->assertTrue($filterType->save()->success());
		
		/**
		 * And a filter
		 */
		$filter = new \Yetti\API\Collection_Filter();
		$filter->setFilterTypeId($filterType->getId());
		$filter->setName('A filter');
		$this->assertTrue($filter->save()->success());
		
		/**
		 * Now check the list
		 */
		$list = new \Yetti\API\Collection_Filter_List();
		$this->assertTrue($list->load($filterType->getId()));
		$this->assertCount(1, $list->getItems());
	}
}
