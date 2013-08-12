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
	 * Load an item type by ID
	 * 
	 * @param int $typeId
	 * @return bool
	 */
	public function load($typeId)
	{
		$list = new \Yetti\API\Item_Type_List();
		
		if ($list->load())
		{
			foreach ($list->getItems() as $type)
			{
				if ($type->getId() == $typeId)
				{
					$this->setJson($type->getJson());
					return true;
				}
			}
		}
		
		return false;
	}
	
	/**
	 * Returns the item type ID
	 * 
	 * @return int
	 */
	public function getId()
	{
		return (int)$this->getJson()->id;
	}
	
	/**
	 * Returns the name of this item type
	 * 
	 * @return string
	 */
	public function getName()
	{
		return (string)$this->getJson()->name;
	}
}
