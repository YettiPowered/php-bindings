<?php

namespace Yetti\API\Tests;
use Yetti\API\User, Yetti\API\User_Address, Yetti\API\Item, Yetti\API\Item_Combination_List, Yetti\API\Order, Yetti\API\Order_Note;

/**
 * Test methods for the order model.
 *
 * @author Sam Holman <sam@yetti.co.uk>
 * @copyright Copyright (c) 2011-2013, Yetti Ltd.
 * @package yetti-api
 * @subpackage tests
 */

class OrderTest extends AuthAbstract
{
	public function testNewOrder()
	{
		/**
		 * Need a user first...
		 */
		$user = new User();
		$user->loadTemplate(1);
		$user->setName('Test user ' . microtime(true));
		$user->setPassword('password');
		$user->setEmail('test' . microtime(true) . '@example.org');
		$this->assertTrue($user->save()->success());
		
		/**
		 * And an address
		 */
		$address = new User_Address();
		$address->setUserId($user->getId());
		$address->setIdentifier('Test address');
		$address->setFirstname('Mike');
		$address->setSurname('Moo');
		$address->setLine1('1 Test Street');
		$address->setLine2('Wibble');
		$address->setCity('Leeds');
		$address->setRegion('West Yorkshire');
		$address->setPostcode('LS1 1AB');
		$address->setTelephone('01234 567890');
		$address->setCountryId(233); // United Kingdom
		$this->assertTrue($address->save()->success());
		
		/**
		 * Create an item
		 */
		$item = new Item();
		$item->loadTemplate(5);
		$item->setName('Test product' . microtime(true));
		$item->setPropertyValue('Name', 'Test product for order');
		$item->setPropertyValue('Description', 'A tasty horse');
		$item->addPricingTier(19);
		$variation = $item->addVariation('Variation');
		$variation->addOption('An option');
		$this->assertTrue($item->save()->success());
		
		$combinationList = new Item_Combination_List();
		$combinationList->load($item->getId());
		$this->assertEquals(2, $combinationList->getTotalItemCount());
		$combination = $combinationList->getItems()->first();
		$combination->setSku('SKU123');
		$this->assertTrue($combination->save()->success());
		
		/**
		 * Now create the order
		 */
		$order = new Order();
		$order->loadTemplate();
		$this->assertFalse($order->save()->success());
		
		$order->setStatusId(1);
		$order->setUserId($user->getId());
		$order->setShippingAddressId($address->getId());
		$order->setBillingAddressId($address->getId());
		$order->addItem($item->getId(), $combination->getId(), 1);
		$this->assertTrue($order->save()->success());
		
		return $order;
	}
	
	/**
	 * @depends testNewOrder
	 */
	public function testLoad(Order $inOrder)
	{
		$order = new Order();
		$this->assertFalse($order->load(-1));
		$this->assertTrue($order->load($inOrder->getId()));
		
		$this->assertEquals('1 Test Street', $order->getShippingAddress()->getLine1());
		$this->assertEquals('1 Test Street', $order->getBillingAddress()->getLine1());
		
		$items = $order->getItems();
		$this->assertCount(1, $items);
		
		$item = new Item();
		$this->assertTrue($item->load($items[0]->getResourceId()));
		$this->assertEquals('Test product for order', $item->getDisplayName());
		
		return $order;
	}
	
	/**
	 * @depends testLoad
	 */
	public function testChangeStatus(Order $inOrder)
	{
		$this->assertEquals(1, $inOrder->getStatusId());
		$inOrder->setStatusId(2);
		$this->assertTrue($inOrder->save()->success());
		
		$order = new Order();
		$this->assertTrue($order->load($inOrder->getId()));
		$this->assertEquals(2, $order->getStatusId());
		
		return $order;
	}
	
	/**
	 * @depends testChangeStatus
	 */
	public function testAddNote(Order $order)
	{
		$note = new Order_Note();
		$this->assertFalse($note->save()->success());
		
		$note->setOrderId($order->getId());
		$this->assertFalse($note->save()->success());
		
		$note->setNote('You arse');
		$this->assertTrue($note->save()->success());
	}
}
