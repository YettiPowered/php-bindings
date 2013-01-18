<?php

namespace Yetti\API;

/**
 * Abstract class for resource list models
 *
 * @author Sam Holman <sam@yetti.co.uk>
 * @copyright Copyright (c) 2011-2012, Yetti Ltd.
 * @package yetti-api
 */

abstract class Resource_ListAbstract extends ListAbstract
{
	private
		
		/**
		 * The request path
		 * 
		 * @var string
		 */
		$_path,
		
		/**
		 * The resource's type ID
		 * 
		 * @var int
		 */
		$_typeId;
	
	/**
	 * Loads the list from Yetti
	 * 
	 * @param int $typeId
	 * @param int $page
	 * @param string $countryCode
	 * @return bool
	 */
	public function load($typeId, $page=null, $countryCode=null)
	{
		$this->_typeId = $typeId;
		
		if ($countryCode && is_string($countryCode)) {
			$countryCode = '/' . strtolower($countryCode);
		}
		
		$this->webservice()->setRequestMethod('get');
		$this->webservice()->setRequestPath($countryCode . '/' . $this->_path . '/' . $typeId . '.ws');
		
		if ($page) {
			$this->webservice()->setRequestParam('page', (int)$page);
		}
		else {
			$this->setAutoPaginate();
		}
		
		if ($this->webservice()->makeRequest())
		{
			$this->setJson($this->webservice()->getResponseJsonObject());
			return true;
		}
		
		return false;
	}
	
	/**
	 * Load the next page of items
	 * 
	 * @return void
	 */
	public function loadNextPage()
	{
		if ($this->shouldAutoPaginate())
		{
			$currentPage = $this->getJson()->listing->currentPage;
			
			if ($this->load($this->_typeId, $currentPage+1)) {
				$this->loadItemObjects();
			}
		}
	}
	
	/**
	 * Loads the list of resource objects
	 * 
	 * @return array
	 */
	protected function loadItemObjects()
	{
		foreach ($this->getJson()->listing->items as $json)
		{
			$item = $this->getNewItemObject();
			$item->setWebservice($this->webservice());
			
			$item->setJson($json);
			$this->addItem($item);
		}
	}
	
	/**
	 * Returns the total number of items available for this listing
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
	 * @return object \Yetti\API\Resource_BaseAbstract
	 */
	abstract protected function getNewItemObject();
}
