<?php

namespace Yetti\API;

/**
 * Item type list model
 *
 * @author Sam Holman <sam@yetti.co.uk>
 * @copyright Copyright (c) 2011-2012, Yetti Ltd.
 * @package yetti-api
 */

class Item_Type_List extends ListAbstract
{
	/**
	 * Load a list of available item types
	 * 
	 * @return bool
	 */
	public function load()
	{
		$this->webservice()->setRequestMethod('get');
		$this->webservice()->setRequestPath('/items.ws');
		
		if ($this->webservice()->makeRequest())
		{
			$this->setJson($this->webservice()->getResponseJsonObject());
			return true;
		}
		
		return false;
	}
	
	/**
	 * Load the item type objects in this list
	 * 
	 * @return array
	 */
	protected function loadItemObjects()
	{
		foreach ($this->getJson()->types as $json)
		{
			$item = new \Yetti\API\Item_Type();
			$item->setJson($json);
			
			$this->addItem($item);
		}
	}
	
	/**
	 * Returns the total number of pages in the list
	 * 
	 * @return int
	 */
	public function getTotalPages()
	{
		return 1;
	}
	
	/**
	 * Returns the current page
	 * 
	 * @return int
	 */
	public function getCurrentPage()
	{
		return 1;
	}
}
