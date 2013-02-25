<?php

namespace Yetti\API\Tests;
use Yetti\API\Order_Status_List;

/**
 * Test methods for the order status list model.
 *
 * @author Sam Holman <sam@yetti.co.uk>
 * @copyright Copyright (c) 2011-2013, Yetti Ltd.
 * @package yetti-api
 * @subpackage tests
 */

class Order_Status_ListTest extends AuthAbstract
{
	public function testLoadList()
	{
		$list = new Order_Status_List();
		$this->assertTrue($list->load());
		
		$status = $list->getItems()->first();
		$this->assertInstanceOf('Yetti\API\Order_Status', $status);
		$this->assertInternalType('int', $status->getId());
		$this->assertInternalType('string', $status->getName());
	}
}
