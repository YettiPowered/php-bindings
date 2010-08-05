<?php

class LabelEdAPI_Items extends LabelEdAPI_Abstract
{
	private $_items;
	
	/**
	 * Returns an array of items or an individual item if requested by id or identifier
	 *
	 * @param mixed $itemId
	 * @return array
	 */
	public function get($itemId=false)
	{
		if ($itemId) {
			return $this->getIndividualItem($itemId);
		}
		else {
			return $this->getItemList();
		}
	}
	
	/**
	 * Create a new item
	 *
	 * @param int $typeId
	 * @param array $itemData
	 * @return bool
	 */
	public function create($typeId, $itemData)
	{
		$this->webservice()->setRequestPath('/templates/item/' . $typeId . '.ws');
		$this->webservice()->setRequestMethod('get');
		$this->webservice()->makeRequest();
		$xml = $this->webservice()->getResponse();
		
		$this->webservice()->setRequestPath('/items.ws');
		$this->webservice()->setRequestMethod('post');
		$this->webservice()->setPostData($xml);
		
		$this->webservice()->makeRequest();
		return $this->webservice()->getResponseCode() == 201;
	}
	
	/**
	 * Modify an existing item
	 *
	 * @param int $itemId
	 * @param array $itemData
	 * @return bool
	 */
	public function modify($itemId, $itemData)
	{
		$this->webservice()->setRequestPath('/items/' . $itemId . '.ws');
		$this->webservice()->setRequestMethod('get');
		$this->webservice()->makeRequest();
		$xml = $this->webservice()->getResponse();
		
		$this->webservice()->resetRequest();
		$this->webservice()->setRequestPath('/items/' . $itemId . '.ws');
		$this->webservice()->setRequestMethod('put');
		$this->webservice()->setPostData($xml);
		
		$this->webservice()->makeRequest();
		return $this->webservice()->getResponseCode() == 200;
	}
	
	/**
	 * Returns a list of items
	 *
	 * @return array
	 */
	private function getItemList()
	{
		if (!$this->_items)
		{
			$this->webservice()->setRequestPath('/items.ws');
			$this->webservice()->setRequestMethod('get');
			$this->webservice()->makeRequest();
			
			$this->_items = $this->webservice()->getResponseXmlObject()->items->item;
		}
		
		return $this->_items;
	}
	
	/**
	 * Return an item as an array
	 *
	 * @param mixed $itemId
	 * @return array
	 */
	private function getIndividualItem($itemId)
	{
		$itemArray = array();
		
		$this->webservice()->setRequestPath('/items/' . $itemId . '.ws');
		$this->webservice()->setRequestMethod('get');
		
		if ($this->webservice()->makeRequest())
		{
			$item = $this->webservice()->getResponseXmlObject();
			
			if ($identifier = $item->xpath('item/identifier'))
			{
				$itemElement = $item->xpath('item');
				
				$itemArray = array('item' => array(
					'identifier'	=> (string)$identifier[0],
					'typeId' 		=> (string)$itemElement[0]->attributes()->typeId,
				));
				
				foreach ($item->xpath('item/language/properties/property') as $property) {
					$itemArray['properties'][(string)$property->attributes()->name] = (string)$property;
				}
			}
		}
		
		return $itemArray;
	}
}
