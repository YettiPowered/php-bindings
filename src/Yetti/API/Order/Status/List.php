<?php

namespace Yetti\API;

/**
 * Order status list model
 *
 * @author Sam Holman <sam@yetti.co.uk>
 * @copyright Copyright (c) 2013, Yetti Ltd.
 * @package yetti-api
 */

class Order_Status_List extends ListAbstract
{
	/**
	 * Load a list of available order statuses
	 * 
	 * @return bool
	 */
	public function load()
	{
		$this->webservice()->setRequestMethod('get');
		$this->webservice()->setRequestPath('/orders/statuses.ws');
		
		if ($this->webservice()->makeRequest())
		{
			$this->setJson($this->webservice()->getResponseJsonObject());
			return true;
		}
		
		return false;
	}
	
	/**
	 * Load the status objects in this list
	 * 
	 * @return array
	 */
	protected function loadItemObjects()
	{
		foreach ($this->getJson()->statuses as $json)
		{
			$item = new Order_Status();
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
