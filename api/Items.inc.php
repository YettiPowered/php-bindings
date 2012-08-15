<?php

namespace Yetti\API;

require_once 'ListAbstract.inc.php';
require_once 'Item.inc.php';

/**
 * API for interfacing with a list of LabelEd items over web services.
 *
 * $Id$
 */

class Items extends ListAbstract
{
	private
		$_counter,
		$_typeClassId;
	
	/**
	 * Loads items by item type class ID
	 *
	 * @param int $typeClassId
	 * @return bool
	 */
	public function load($typeClassId, $page=1)
	{
		$this->_typeClassId = $typeClassId;
		
		$this->webservice()->setRequestPath('/items/' . $typeClassId . '.ws');
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
				if ($this->load($this->_typeClassId, $currentPage+1)) {
					$return = array_merge($return, $this->getItemList());
				}
			}
		}
		
		return $return;
	}
}
