<?php

namespace Yetti\API;
use Yetti\API\Order;

/**
 * Order list model
 *
 * @author Sam Holman <sam@yetti.co.uk>
 * @copyright Copyright (c) 2011-2013, Yetti Ltd.
 * @package yetti-api
 */

class Order_List extends Resource_ListAbstract
{
	/**
	 * Load a list of orders
	 * 
	 * @return bool
	 */
	public function load($status=null)
	{
		$this->webservice()->setRequestMethod('get');
		$this->webservice()->setRequestPath('/orders.ws');
		$this->webservice()->setRequestParam('status', $status);
		
		if ($this->webservice()->makeRequest())
		{
			$this->setJson($this->webservice()->getResponseJsonObject());
			return true;
		}
		
		return false;
	}
	
	/**
	 * Returns a new order object
	 * 
	 * @return \Yetti\API\Order
	 */
	protected function getNewItemObject()
	{
		return new Order();
	}
}
