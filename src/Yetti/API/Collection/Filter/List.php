<?php

namespace Yetti\API;

/**
 * Collection filter list model
 *
 * @author Sam Holman <sam@yetti.co.uk>
 * @copyright Copyright (c) 2011-2013, Yetti Ltd.
 * @package yetti-api
 */

class Collection_Filter_List extends ListAbstract
{
	/**
	 * Load a list of filters for the given filter type
	 * 
	 * @param int $filterTypeId
	 * @return bool
	 */
	public function load($filterTypeId)
	{
		$this->webservice()->setRequestMethod('get');
		$this->webservice()->setRequestPath('/collections/filters/null/' . $filterTypeId . '.ws');
		
		if ($this->webservice()->makeRequest())
		{
			$this->setJson($this->webservice()->getResponseJsonObject());
			return true;
		}
		
		return false;
	}
	
	/**
	 * Load the filter objects in this list
	 * 
	 * @return array
	 */
	protected function loadItemObjects()
	{
		foreach ($this->getJson()->filters as $json)
		{
			$item = new \Yetti\API\Collection_Filter();
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
