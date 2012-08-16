<?php

namespace Yetti\API\Tests;

/**
 * Test methods for item category assignment
 *
 * @author Sam Holman <sam@yetti.co.uk>
 * @copyright Copyright (c) 2011-2012, Yetti Ltd.
 * @package yetti-api
 * @subpackage tests
 */

class Item_CollectionsTest extends AuthAbstract
{
	public function testCategoryAssignment()
	{
		/**
		 * First let's make a new item
		 */
		$item = new \Yetti\API\Item();
		$this->assertTrue($item->loadTemplate(4));
		
		$item->setName('A test item ' . microtime(true));
		$item->setPropertyValue('Name', 'Test item');
		$item->setPropertyValue('Body', 'A test body');
		$this->assertTrue($item->save()->success());
		$itemId = $item->getId();
		
		/**
		 * And a new collection
		 */
		$collection = new \Yetti\API\Collection();
		$this->assertTrue($collection->loadTemplate(4));
		$collection->setName('A test collection ' . microtime(true));
		$collection->setPropertyValue('Name', 'Test collection');
		$this->assertTrue($collection->save()->success());
		$collectionId = $collection->getId();
		
		$itemCollections = new \Yetti\API\Item_Collections();
		$this->assertTrue($itemCollections->load($itemId));
		
		/**
		 * There should be no category assignments yet...
		 */
		$this->assertEquals(0, count($item->getCollectionIds()));
		$this->assertEquals(0, count($itemCollections->getCollectionIds()));
		
		/**
		 * Assign the item to the category.
		 */
		$itemCollections->addCollection($collectionId);
		$this->assertTrue($itemCollections->save()->success());
		
		/**
		 * And check.
		 */
		$item = new \Yetti\API\Item();
		$this->assertTrue($item->load($itemId));
		
		$itemCollections = new \Yetti\API\Item_Collections();
		$this->assertTrue($itemCollections->load($itemId));
		
		$this->assertEquals(1, count($item->getCollectionIds()));
		$this->assertEquals(1, count($itemCollections->getCollectionIds()));
	}
}
