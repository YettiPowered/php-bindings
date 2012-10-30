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
		
		$this->assertTrue($item->isLanguageActive());
		$this->assertEquals('a-test-item', substr($item->getName(), 0, 11));
		$this->assertEquals('Test item', $item->getPropertyValue('Name'));
		
		return $item;
	}
	
	/**
	 * @depends testLoadItem
	 */
	public function testAttachedAssets(\Yetti\API\Item $inItem)
	{
		$relatedItem = new \Yetti\API\Item();
		$relatedItem->loadTemplate(4);
		$relatedItem->setName('A test related item' . microtime(true));
		$relatedItem->setPropertyValue('Name', 'Test item');
		$relatedItem->setPropertyValue('Body', 'A test body');
		$this->assertTrue($relatedItem->save()->success());
		
		$this->assertEmpty($inItem->getAttachedAssets());
		$this->assertEmpty($inItem->getAttachedAssets('Related'));
		$this->assertEmpty($inItem->getAttachedAssets('Test'));
		
		$inItem->addAsset('Related', $relatedItem->getId(), 'alt', 'url');
		$this->assertTrue($inItem->save()->success());
		
		$item = new \Yetti\API\Item();
		$this->assertTrue($item->load($inItem->getId()));
		
		$fullAttachedAssets = $inItem->getAttachedAssets();
		$relatedArticles	= $inItem->getAttachedAssets('Related');
		
		$this->assertNotEmpty($fullAttachedAssets);
		$this->assertNotEmpty($relatedArticles);
		$this->assertEmpty($inItem->getAttachedAssets('Test'));
		
		$this->assertInternalType('array', $fullAttachedAssets);
		$this->assertInstanceOf('\Yetti\API\Resource_Asset_Group', $fullAttachedAssets['Related']);
		$this->assertInstanceOf('\Yetti\API\Resource_Asset_Group', $relatedArticles);
		
		$this->assertCount(1, $fullAttachedAssets);
		$this->assertCount(1, $relatedArticles->getItems());
		
		$this->assertEquals($relatedItem->getId(), $relatedArticles->getItems()->first()->getId());
		$this->assertEquals('alt', $relatedArticles->getItems()->first()->getAltText());
		$this->assertEquals('url', $relatedArticles->getItems()->first()->getUrl());
		
		return $item;
	}
	
	/**
	 * @depends testAttachedAssets
	 */
	public function testAssignItemToCategory(\Yetti\API\Item $inItem)
	{
		/**
		 * Make a new collection
		 */
		$collection = new \Yetti\API\Collection();
		$this->assertTrue($collection->loadTemplate(4));
		$collection->setName('A test collection ' . microtime(true));
		$collection->setPropertyValue('Name', 'Test collection');
		$this->assertTrue($collection->save()->success());
		$collectionId = $collection->getId();
		
		$this->assertEquals(0, count($inItem->getCollectionIds()));
		$inItem->assignToCollection($collectionId);
		$this->assertTrue($inItem->save()->success());
		
		$item = new \Yetti\API\Item();
		$this->assertTrue($item->load($inItem->getId()));
		$this->assertEquals(1, count($item->getCollectionIds()));
		
		return $item;
	}
	
	/**
	 * @depends testAssignItemToCategory
	 */
	public function testGetAuthor(\Yetti\API\Item $inItem)
	{
		$item = new \Yetti\API\Item();
		$this->assertTrue($item->load($inItem->getId()));
		
		$this->assertEquals('api-test', $item->getOriginalAuthor()->getName());
		$this->assertEquals('api-test', $item->getRevisionAuthor()->getName());
		
		return $item;
	}
	
	/**
	 * @depends testGetAuthor
	 */
	public function testDelete(\Yetti\API\Item $inItem)
	{
		$item = new \Yetti\API\Item();
		$this->assertTrue($item->load($inItem->getId()));
		
		$this->assertTrue($item->delete()->success());
		
		$item = new \Yetti\API\Item();
		$this->assertFalse($item->load($inItem->getId()));
	}
	
	public function testSpecialCharacters()
	{
		$item = new \Yetti\API\Item();
		$this->assertTrue($item->loadTemplate(4));
		$item->setName('This & That ' . microtime(true));
		$item->setPropertyValue('Name', 'This & That');
		$item->setPropertyValue('Body', '<div>This & That</div>');
		$this->assertTrue($item->save()->success());
		$itemId = $item->getId();
		
		$item = new \Yetti\API\Item();
		$this->assertTrue($item->load($itemId));
		
		$this->assertEquals('this--that', substr($item->getName(), 0, 10));
		$this->assertEquals('This & That', $item->getPropertyValue('Name'));
		$this->assertEquals('<div>This & That</div>', $item->getPropertyValue('Body'));
		$this->assertTrue($item->save()->success());
		
		$item = new \Yetti\API\Item();
		$this->assertTrue($item->load($itemId));
		
		$this->assertEquals('this--that', substr($item->getName(), 0, 10));
		$this->assertEquals('This & That', $item->getPropertyValue('Name'));
		$this->assertEquals('<div>This & That</div>', $item->getPropertyValue('Body'));
	}
	
	public function testSetLanguageActive()
	{
		$item = new \Yetti\API\Item();
		$this->assertTrue($item->loadTemplate(4));
		$item->setLanguageActive(false);
		$item->setName('A test item ' . microtime(true));
		$item->setPropertyValue('Name', 'Test item');
		$item->setPropertyValue('Body', 'A test body');
		$this->assertTrue($item->save()->success());
		$itemId = $item->getId();
		
		$item = new \Yetti\API\Item();
		$this->assertTrue($item->load($itemId));
		$this->assertFalse($item->isLanguageActive());
	}
	
	public function testAddPricingTier()
	{
		$item = new \Yetti\API\Item();
		$this->assertTrue($item->loadTemplate(5));
		
		$item->setName('A test product' . microtime(true));
		$item->setPropertyValue('Name', 'Test product');
		$item->setPropertyValue('Description', 'A test product');
		$this->assertFalse($item->save()->success());
		
		$item->addPricingTier(10.00);
		$this->assertTrue($item->save()->success());
		
		return $item;
	}
	
	/**
	 * @depends testAddPricingTier
	 */
	public function testLoadProduct(\Yetti\API\Item $inItem)
	{
		$item = new \Yetti\API\Item();
		$this->assertTrue($item->load($inItem->getId()));
		
		$this->assertEquals(10, $item->getPrice());
	}

	/**
	 * @depends testAddPricingTier
	 */
	public function testPricingTierErrorNotPresent(\Yetti\API\Item $inItem)
	{
		$item = new \Yetti\API\Item();
		$this->assertTrue($item->loadTemplate(5));

		$item->setName($inItem->getName());
		$item->setPropertyValue('Name', 'Test product 2');
		$item->setPropertyValue('Description', 'A test product 2');
		
		$result = $item->save();
		$this->assertFalse($result->success());
		$errors = $result->getErrors();
		
		// Should be errors based on identifier *and* tiers (as a tier must have a price > 0)
		$this->assertArrayHasKey('identifier', $errors);
		$this->assertArrayHasKey('tiers', $errors);
		
		// Add a pricing tier
		$item->addPricingTier(11.00);
		
		$result = $item->save();
		$this->assertFalse($result->success());
		$errors = $result->getErrors();
		
		// We expect that the "identifier" error is present, but not the "tiers" error
		$this->assertArrayHasKey('identifier', $errors);
		$this->assertArrayNotHasKey('tiers', $errors);
	}
}
