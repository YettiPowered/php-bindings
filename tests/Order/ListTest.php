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
			
		}
		else {
			$this->markTestSkipped('No existing orders');
		}
	}
}
