<?php

namespace Yetti\API\Tests;
use Yetti\API\Item;
use Yetti\API\Item_Combination_List;

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
	public function testLoad()
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
	}
}
