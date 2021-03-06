<?php

namespace Yetti\API;

/**
 * Item list model
 *
 * @author Sam Holman <sam@yetti.co.uk>
 * @copyright Copyright (c) 2011-2012, Yetti Ltd.
 * @package yetti-api
 */

class Item_List extends Resource_ListAbstract
{
	/**
	 * Construct a new item list model
	 * 
	 * @return void
	 */
	public function __construct()
	{
		$this->setPath('items');
	}
	
	/**
	 * Returns a new item object
	 * 
	 * @return object \Yetti\API\Item
	 */
	protected function getNewItemObject()
	{
		return new \Yetti\API\Item();
	}
}
