<?php

namespace Yetti\API;

/**
 * Abstract class for resource list models
 *
 * @author Sam Holman <sam@yetti.co.uk>
 * @copyright Copyright (c) 2011-2012, Yetti Ltd.
 * @package yetti-api
 */

abstract class ResourceListAbstract extends ListAbstract
{
	private
		$_path,
		$_typeId,
		$_counter;
	
	/**
	 * Loads the list from Yetti
	 *
	 * @param int $typeId
	 * @param int $page
	 * @return bool
	 */
	public function load($typeId, $page=1)
	{
		$this->_typeId = $typeId;
		
		$this->webservice()->setRequestMethod('get');
		$this->webservice()->setRequestPath('/' . $this->_path . '/' . $typeId . '.ws');
		$this->webservice()->setRequestParam('page', (int)$page);
		
		if ($this->webservice()->makeRequest())
		{
			$this->setJson($this->webservice()->getResponseJsonObject());
			return true;
		}
		
		return false;
	}
	
	/**
	 * Loads the list of resource objects
	 * 
	 * @return array
	 */
	protected function loadItemObjects()
	{
		$this->_counter = 1;
		$this->loadItemList();
	}
	
	/**
	 * Returns an item list, paginating automatically where appropriate
	 * 
	 * @return void
	 */
	private function loadItemList()
	{
		$items		 = $this->getJson()->listing->items;
		$currentMax  = $this->getJson()->listing->currentMax;
		$currentPage = $this->getJson()->listing->currentPage;
		$totalPages  = $this->getJson()->listing->totalPages;
		
		foreach ($items as $json)
		{
			$item = $this->getNewItemObject();
			
			$item->setJson($json);
			$this->addItem($item);
			
			$this->_counter++;
			
			if ($this->_counter == $currentMax && $currentPage < $totalPages)
			{
				if ($this->load($this->_typeId, $currentPage+1)) {
					$this->loadItemList();
				}
			}
		}
	}
	
	/**
	 * Returns the total number of items avaliable for this listing
	 * 
	 * @return int
	 */
	public function getTotalItemCount()
	{
		return (int)$this->getJson()->listing->totalItems;
	}
	
	/**
	 * Returns the total number of pages available in this listing
	 * 
	 * @return int
	 */
	public function getTotalPages()
	{
		return (int)$this->getJson()->listing->totalPages;
	}
	
	/**
	 * Returns the currently loaded page number
	 * 
	 * @return int
	 */
	public function getCurrentPage()
	{
		return (int)$this->getJson()->listing->currentPage;
	}
	
	/**
	 * Set the request path
	 * 
	 * @param string $path
	 * @return void
	 */
	protected function setPath($path)
	{
		if (is_string($path)) {
			$this->_path = $path;
		}
	}
	
	/**
	 * Returns a new object for population from the list
	 * 
	 * @return object \Yetti\API\ResourceAbstract
	 */
	abstract protected function getNewItemObject();
}
