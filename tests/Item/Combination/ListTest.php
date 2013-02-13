<?php

namespace Yetti\API\Tests;
use Yetti\API\Item_Combination;

use Yetti\API\Item, Yetti\API\Item_Combination_List;

/**
 * Test methods for listing item combinations
 *
 * @author Sam Holman <sam@yetti.co.uk>
 * @copyright Copyright (c) 2012, Yetti Ltd.
 * @package yetti-api
 * @subpackage tests
 */

class Item_Combination_ListTest extends AuthAbstract
{
	public function testLoadList()
	{
		/**
		 * First need a product
		 */
		$item = new Item();
		$this->assertTrue($item->loadTemplate(5));
		
		$item->setName('A test product' . microtime(true));
		$item->setPropertyValue('Name', 'Test product');
		$item->setPropertyValue('Description', 'A test product');
		$item->addPricingTier(10.00);
		$this->assertTrue($item->save()->success());

		/**
		 * Now we can check combinations. Currently there should be 1 (the default combination).
		 */
		$combinations = new Item_Combination_List();
		$combinations->load($item->getId());
		$this->assertEquals(1, $combinations->getTotalItemCount());
		
		/**
		 * Add some item variations
		 */
		
		$variation1 = $item->addVariation('Variation 1');
		$variation2 = $item->addVariation('Variation 2');
		
		$variation1->addOption('Option 1');
		$variation1->addOption('Option 2');
		$variation2->addOption('Option 3');
		$variation2->addOption('Option 4');
		
		$this->assertTrue($item->save()->success());
		
		/**
		 * Should be more combinations now...
		 */
		$combinations = new Item_Combination_List();
		$combinations->load($item->getId());
		$this->assertEquals(5, $combinations->getTotalItemCount());
		
		/**
		 * Let's check a couple of them
		 */
		$combination = $combinations->getItems()->first();
		$this->assertEmpty($combination->getOptions()); // This is the default combination, therefore no options.
		
		$combination = $combinations->getItems()->next();
		$this->assertTrue($combination->isAvailable());
		$this->assertEquals(0, $combination->getStock());
		$this->assertEmpty($combination->getSku());
		$this->assertEquals(10, $combination->getPrice());
		
		$options = $combination->getOptions();
		$this->assertEquals('Option 1', $options['Variation 1']);
		$this->assertEquals('Option 3', $options['Variation 2']);
		
		return $combinations;
	}
	
	/**
	 * @depends testLoadList
	 */
	public function testSetUseStockControl(Item_Combination_List $combinations)
	{
		$this->assertFalse($combinations->isUsingStockControl());
		$combinations->setUsingStockControl();
		$this->assertTrue($combinations->save()->success());
		$itemId = $combinations->getItemId();
		
		$combinations = new Item_Combination_List();
		$this->assertTrue($combinations->load($itemId));
		$this->assertTrue($combinations->isUsingStockControl());
		
		return $combinations->getItems()->first();
	}
	
	/**
	 * @depends testSetUseStockControl
	 */
	public function testSaveCombination(Item_Combination $inCombination)
	{
		$inCombination->setStock(10);
		$inCombination->setSku('sku123');
		$this->assertTrue($inCombination->save()->success());
		
		$combination = new Item_Combination();
		$this->assertTrue($combination->load($inCombination->getId()));
		
		$this->assertEquals(10, $combination->getStock());
		$this->assertEquals('sku123', $combination->getSku());
	}
}
