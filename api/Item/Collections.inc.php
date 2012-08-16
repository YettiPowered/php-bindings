<?php

namespace Yetti\API;

/**
 * Item collections model
 * Responsible for listing collections to which an item is assigned
 * And assigning an item to one or more collections.
 *
 * @author Sam Holman <sam@yetti.co.uk>
 * @copyright Copyright (c) 2011-2012, Yetti Ltd.
 * @package yetti-api
 */

class Item_Collections extends BaseAbstract
{
	private
		$_resourceId;
		
	/**
	 * Loads a list of collections to which the given item is assigned
	 *
	 * @param mixed $resourceId int ID or string identifier
	 * @return bool
	 */
	public function load($resourceId)
	{
		$this->webservice()->setRequestPath('/items/collections' . '.ws');
		$this->webservice()->setRequestParam('itemId', $resourceId);
		$this->webservice()->setRequestMethod('get');
		
		if ($this->webservice()->makeRequest())
		{
			$this->_resourceId = $resourceId;
			$this->setJson($this->webservice()->getResponseJsonObject());
			return true;
		}
		
		return false;
	}
	
	/**
	 * Save this item's collection data
	 * 
	 * @return \Yetti\API\Result
	 */
	public function save()
	{
		if ($this->_resourceId)
		{
			$this->webservice()->setRequestPath('/items/collections' . '.ws');
			$this->webservice()->setRequestParam('itemId', $this->_resourceId);
			$this->webservice()->setRequestMethod('put');
			
			$this->webservice()->setPostData(json_encode($this->getJson()));
			return $this->makeRequestReturnResult();
		}
		
		return false;
	}
	
	/**
	 * Returns an array of collection IDs
	 * 
	 * @return array
	 */
	public function getCollectionIds()
	{
		$return = array();
		
		foreach ($this->getJson()->collections as $collectionId) {
			$return[] = (int)$collectionId;
		}
		
		return $return;
	}
	
	/**
	 * Adds a new collection ID to the list
	 * 
	 * @param int $collectionId
	 * @return void
	 */
	public function addCollection($collectionId)
	{
		$this->getJson()->collections[] = (int)$collectionId;
	}
	
	/**
	 * Removes a collection from the list
	 * 
	 * @param int $collectionId
	 * @return bool
	 */
	public function removeCollection($collectionId)
	{
		$count = 0;
		
		foreach ($this->getJson()->collections->collection as $key => $collection)
		{
			if ($collectionId == (int)((string)$collection))
			{
				unset($this->getJson()->collections->collection[$count]);
				return true;
			}
			
			$count++;
		}
		
		return false;
	}
}
