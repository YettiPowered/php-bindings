<?php

namespace Yetti\API\Tests;
use Yetti\API\Order_List;

/**
 * Test methods for the order list model.
 *
 * @author Sam Holman <sam@yetti.co.uk>
 * @copyright Copyright (c) 2011-2013, Yetti Ltd.
 * @package yetti-api
 * @subpackage tests
 */

class Order_ListTest extends AuthAbstract
{
	public function testLoadList()
	{
		$orderList = new Order_List();
		$orderList->load();
		
		if ($orderList->getTotalItemCount())
		{
			$order = $orderList->getItems()->first();
			$this->assertInstanceOf('Yetti\API\Order', $order);
			$order->setStatusId(1);
			$this->assertTrue($order->save()->success());
			
			$orderList = new Order_List();
			$orderList->load(1);
			
			$this->assertGreaterThan(0, $orderList->getTotalItemCount());
			$this->assertEquals(1, $orderList->getItems()->first()->getStatusId());
			
			$orderList = new Order_List();
			$orderList->load(2);
			
			$this->assertGreaterThan(0, $orderList->getTotalItemCount());
			$this->assertEquals(2, $orderList->getItems()->first()->getStatusId());
		}
		else {
			$this->markTestSkipped('No existing orders');
		}
	}
}
