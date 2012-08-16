<?php

namespace Yetti\API\Tests;

/**
 * Test methods for the item model.
 *
 * @author Sam Holman <sam@yetti.co.uk>
 * @copyright Copyright (c) 2011-2012, Yetti Ltd.
 * @package yetti-api
 * @subpackage tests
 */

class ItemTest extends AuthAbstract
{
	/**
	 * @expectedException \Yetti\API\Exception
	 */
	public function testSaveFailsWithoutLoadingTemplate()
	{
		$item = new \Yetti\API\Item();
		$item->save();
	}
	
	public function testFailToSaveInvalidItem()
	{
		$item = new \Yetti\API\Item();
		$this->assertTrue($item->loadTemplate(9999));
		$this->assertFalse($item->save()->success());
	}
	
	public function testSaveValidItem()
	{
		$item = new \Yetti\API\Item();
		$this->assertTrue($item->loadTemplate(4));
		$this->assertFalse($item->save()->success());
		
		/**
		 * Item type "4" on the API test site requires that
		 * the "Name" and "Body" properties be set.
		 */
		
		$item->setName('A test item ' . microtime(true)); // Name (identifier) has to be unique
		$this->assertFalse($item->save()->success());
		
		$item->setPropertyValue('Name', 'Test item');
		$this->assertFalse($item->save()->success());
		
		$item->setPropertyValue('Body', 'A test body');
		$this->assertTrue($item->save()->success());
		
		return $item;
	}
	
	/**
	 * @depends testSaveValidItem
	 */
	public function testLoadItem(\Yetti\API\Item $inItem)
	{
		$item = new \Yetti\API\Item();
		$this->assertFalse($item->load(-1)); // Invalid item
		$this->assertTrue($item->load($inItem->getId()));
		
		$this->assertEquals('a-test-item', substr($item->getName(), 0, 11));
		$this->assertEquals('Test item', $item->getPropertyValue('Name'));
	}
}
