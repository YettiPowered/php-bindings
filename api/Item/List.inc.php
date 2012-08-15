<?php

namespace Yetti\API;

/**
 * Item list model
 *
 * @author Sam Holman <sam@yetti.co.uk>
 * @copyright Copyright (c) 2011-2012, Yetti Ltd.
 * @package yetti-api
 */

class Item_List extends ListAbstract
{
	private
		$_counter,
		$_typeId;
	
	/**
	 * Loads items by item type ID
	 *
	 * @param int $typeId
	 * @param int $page
	 * @return bool
	 */
	public function load($typeId, $page=1)
	{
		$this->_typeId = $typeId;
		
		$this->webservice()->setRequestPath('/items/' . $typeId . '.ws');
		$this->webservice()->setRequestParam('page', (int)$page);
		$this->webservice()->setRequestMethod('get');
		
		if ($this->webservice()->makeRequest())
		{
			$this->setJson($this->webservice()->getResponseJsonObject());
			return true;
		}
		
		return false;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see ListAbstract::getItems()
	 */
	public function getItems()
	{
		$this->_counter = 1;
		return $this->getItemList();
	}
	
	/**
	 * Returns an item list, paginating automatically where appropriate
	 * 
	 * @return array
	 */
	private function getItemList()
	{
		$return = array();
		
		$items		 = $this->getJson()->listing->items;
		$currentMax  = $this->getJson()->listing->currentMax;
		$currentPage = $this->getJson()->listing->currentPage;
		$totalPages  = $this->getJson()->listing->totalPages;
		
		foreach ($items as $json)
		{
			$item = new Item();
			$item->setJson($json);
			$return[] = $item;
			
			$this->_counter++;
			
			if ($this->_counter == $currentMax && $currentPage < $totalPages)
			{
				if ($this->load($this->_typeId, $currentPage+1)) {
					$return = array_merge($return, $this->getItemList());
				}
			}
		}
		
		return $return;
	}
}
