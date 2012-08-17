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
	
	public function testListLoadsItemsMarkedUnavailable()
	{
		$list = new \Yetti\API\Item_List();
		$this->assertTrue($list->load(4));
		$this->assertGreaterThan(0, $itemCount = $list->getTotalItemCount());
		
		/**
		 * Now make a new (unavailable item)
		 */
		$item = new \Yetti\API\Item();
		$this->assertTrue($item->loadTemplate(4));
		$item->setLanguageActive(false);
		$item->setName('Testing an unavailable item ' . microtime(true));
		$item->setPropertyValue('Name', 'Test item');
		$item->setPropertyValue('Body', 'A test body');
		$this->assertTrue($item->save()->success());
		
		$list = new \Yetti\API\Item_List();
		$this->assertTrue($list->load(4));
		$this->assertGreaterThan($itemCount, $list->getTotalItemCount());
		
		foreach ($list->getItems() as $item)
		{
			if (!$item->isLanguageActive()) {
				return;
			}
		}
		
		$this->fail("List didn't contain any inactive items.");
	}
}
