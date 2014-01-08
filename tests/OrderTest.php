<?php

namespace Yetti\API\Tests;
use Yetti\API\User, Yetti\API\User_Address, Yetti\API\Item, Yetti\API\Item_Combination_List, Yetti\API\Order, Yetti\API\Order_Note, Yetti\API\Order_Shipment;

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
		$combinations = $combinationList->getItems();
		$combination = $combinations[1];
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
		$order->setCourierId(1);
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
		
		return $order;
	}
	
	/**
	 * @depends testAddNote
	 */
	public function testCreateShipment(Order $order)
	{
		$shipment = new Order_Shipment();
		$this->assertFalse($shipment->save()->success());
		
		$shipment->setOrderId($order->getId());
		$this->assertFalse($shipment->save()->success());
		
		$shipment->setCourierId($order->getCourierId());
		$shipment->setPackageId(1);
		$shipment->setTracking('abc123');
		
		$items = $order->getItems();
		$shipment->addItem($items[0]->getId());
		
		$this->assertTrue($shipment->save()->success());
		
		return $order;
	}
	
	/**
	 * @depends testCreateShipment
	 */
	public function testLoadNotesAndShipments(Order $inOrder)
	{
		$order = new Order();
		$this->assertTrue($order->load($inOrder->getId()));
		
		$notes = $order->getNotes();
		$this->assertEquals('You arse', $notes[0]->getNote());
		
		$shipments = $order->getShipments();
		$this->assertEquals('abc123', $shipments[0]->getTracking());
		
		return $order;
	}
	
	/**
	* @depends testLoadNotesAndShipments
	*/
	public function testOrderItemDetails(Order $order)
	{
	    $items = $order->getItems();
	    
	    $this->assertEquals('Test product for order', $items[0]->getName());
	    
	    $this->assertEquals(19, $items[0]->getPrice());
	    $this->assertEquals(22.80, $items[0]->getPriceIncVat());
	    $this->assertEquals(20, $items[0]->getVatRate());
	    
	    $this->assertEquals(1, $items[0]->getQuantity());
	    
	    $this->assertEquals('SKU123', $items[0]->getSku());
	    
	    $options = $items[0]->getCombinationOptions();
	    $this->assertEquals(1, count($options));
	    $this->assertEquals($options[0]->name, 'An option');
	}
}
