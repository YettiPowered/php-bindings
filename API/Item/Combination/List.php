<?php

namespace Yetti\API;

/**
 * Item combinations list model
 * Responsible for listing combinations for a product item.
 *
 * @author Sam Holman <sam@yetti.co.uk>
 * @copyright Copyright (c) 2012, Yetti Ltd.
 * @package yetti-api
 */

class Item_Combination_List extends Resource_ListAbstract
{
	/**
	 * Construct a new item combination list model
	 * 
	 * @return void
	 */
	public function __construct()
	{
		$this->setPath('items/combinations');
	}
	
	/**
	 * Returns a new item combination object
	 * 
	 * @return object \Yetti\API\Item_Combination
	 */
	protected function getNewItemObject()
	{
		return new \Yetti\API\Item_Combination();
	}
}
