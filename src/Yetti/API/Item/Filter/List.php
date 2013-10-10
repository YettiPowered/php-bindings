<?php

namespace Yetti\API;

/**
 * Filter list model
 *
 * @author Sam Holman <sam@yetti.co.uk>
 * @copyright Copyright (c) 2011-2013, Yetti Ltd.
 * @package yetti-api
 */

class Item_Filter_List extends ListAbstract
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
		$this->webservice()->setRequestPath('/filters/items/null/' . $filterTypeId . '.ws');
		
		if ($this->webservice()->makeRequest())
		{
			$this->setJson($this->webservice()->getResponseJsonObject());
			return true;
		}
		
		return false;
	}
	
	/**
	 * Load a list of filters specifically attached to the given item
	 * 
	 * @param int $itemId
	 * @param int $filterTypeId
	 * @return bool
	 */
	public function loadByItemId($itemId, $filterTypeId=null)
	{
		$path = '/items/-1/' . $itemId;
		
		if ($filterTypeId) {
			$path .= '/' . $filterTypeId;
		}
		
		$path .= '/filters.ws';
		
		$this->webservice()->setRequestMethod('get');
		$this->webservice()->setRequestPath($path);
		
		if ($this->webservice()->makeRequest())
		{
			$this->setJson($this->webservice()->getResponseJsonObject());
			return true;
		}
		
		return false;
	}
	
	/**
	 * Clear filters for the given item
	 * 
	 * @param int $itemId
	 * @param int $filterTypeId
	 * @return \Yetti\API\Result
	 */
	public function clearFiltersForItem($itemId, $filterTypeId=null)
	{
		$path = '/items/-1/' . $itemId;
		
		if ($filterTypeId) {
			$path .= '/' . $filterTypeId;
		}
		
		$path .= '/filters.ws';
		
		$filters = new \stdClass();
		$filters->filters = array();
		$this->setJson($filters);
		
		$this->webservice()->setRequestMethod('put');
		$this->webservice()->setRequestPath($path);
		$this->webservice()->setPostData(json_encode($this->getJson()));
		return $this->makeRequestReturnResult();
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
			$item = new \Yetti\API\Item_Filter();
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
