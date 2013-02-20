<?php

namespace Yetti\API\Tests;
use Yetti\API\User, Yetti\API\Order;

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
		 * Grab a user first...
		 */
		$user = new User();
		$this->assertTrue($user->load(1));
		
		/**
		 * Now create the order
		 */
		$order = new Order();
		$order->loadTemplate();
		$this->assertFalse($order->save()->success());
		
		$order->setStatusId(1);
		$order->setUserId($user->getId());
		$this->assertTrue($order->save()->success());
		
		return $order;
	}
	
	/**
	 * @depends testNewOrder
	 */
	public function testLoad(Order $order)
	{
		
	}
}
