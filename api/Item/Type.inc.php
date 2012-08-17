<?php

namespace Yetti\API;

/**
 * Item type model.
 * 
 * @author Sam Holman <sam@yetti.co.uk>
 * @copyright Copyright (c) 2011-2012, Yetti Ltd.
 * @package yetti-api
 */

class Item_Type extends BaseAbstract
{
	/**
	 * Returns the item type ID
	 * 
	 * @return int
	 */
	public function getId()
	{
		return $this->getJson()->id;
	}
}
