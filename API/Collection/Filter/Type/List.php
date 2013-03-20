<?php

namespace Yetti\API;

/**
 * Collection filter type list model
 *
 * @author Sam Holman <sam@yetti.co.uk>
 * @copyright Copyright (c) 2011-2013, Yetti Ltd.
 * @package yetti-api
 */

class Collection_Filter_Type_List extends ListAbstract
{
	/**
	 * Load a list of filter types for the given collection
	 * 
	 * @param int $collectionId
	 * @return bool
	 */
	public function load($collectionId)
	{
		$this->webservice()->setRequestMethod('get');
		$this->webservice()->setRequestPath('/collections/filters/' . $collectionId . '.ws');
		
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
			$item = new \Yetti\API\Collection_Filter_Type();
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
