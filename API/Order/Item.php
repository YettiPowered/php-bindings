<?php

namespace Yetti\API;

/**
 * Order item model.
 * 
 * @author Sam Holman <sam@yetti.co.uk>
 * @copyright Copyright (c) 2011-2013, Yetti Ltd.
 * @package yetti-api
 */

class Order_Item extends BaseAbstract
{
	/**
	 * Returns the order resource ID
	 * 
	 * @return int
	 */
	public function getId()
	{
		return (int)$this->getJson()->id;
	}
	
	/**
	 * Returns the item resource ID
	 * 
	 * @return int
	 */
	public function getResourceId()
	{
		return (int)$this->getJson()->resource->resourceId;
	}
	
	/**
	 * Returns the selected order item's combination ID
	 * 
	 * @return int
	 */
	public function getCombinationId()
	{
		return (int)$this->getJson()->combination->id;
	}
	
	/**
	 * Returns the order item's SKU
	 * 
	 * @return string
	 */
	public function getSku()
	{
		return (string)$this->getJson()->combination->sku;
	}
	
	/**
	 * Returns the quantity of this item ordered
	 * 
	 * @return float
	 */
	public function getQuantity()
	{
		return (float)$this->getJson()->quantity;
	}
}
