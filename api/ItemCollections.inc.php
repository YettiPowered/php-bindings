<?php

namespace Yetti\API;

require_once 'BaseAbstract.inc.php';

/**
 * API for interfacing with LabelEd collection items
 * $Id$
 */

class ItemCollections extends BaseAbstract
{
	private
		$_resourceId;
		
	/**
	 * Loads an items collections
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
	 * Returns an array of collectionIds
	 * 
	 * @return array
	 */
	public function getCollectionIds()
	{
		$return = array();
		
		foreach ($this->getJson()->collections->collection as $collectionId) {
			$return[] = (int)((string)$collectionId);
		}
		
		return $return;
	}
	
	/**
	 * Adds a new collectionId to the list
	 * 
	 * @param int $collectionId
	 * @return void
	 */
	public function addCollection($collectionId)
	{
		$this->getJson()->collections->addChild('collection', (int)$collectionId);
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
	
	/**
	 * (non-PHPdoc)
	 * @see ResourceAbstract::update()
	 */
	public function save()
	{
		if ($this->_resourceId)
		{
			$this->webservice()->setRequestPath('/items/collections' . '.ws');
			$this->webservice()->setRequestParam('itemId', $this->_resourceId);
			$this->webservice()->setRequestMethod('put');
			
			$this->webservice()->setPostData($this->getJson()->asXML());
			return $this->makeRequestReturnResult();
		}
		
		return false;
	}
}
