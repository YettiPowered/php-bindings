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
	 * @param array $itemArray
	 * @return bool
	 */
	public function create($itemArray)
	{
		$typeId 	= !empty($itemArray['item']['typeId']) ? $itemArray['item']['typeId'] : false;
		$identifier = !empty($itemArray['item']['identifier']) ? $itemArray['item']['identifier'] : false;
		$properties = !empty($itemArray['properties']) && is_array($itemArray['properties']) ?
			$itemArray['properties'] : false;
		
		$this->webservice()->setRequestPath('/templates/item/' . $typeId . '.ws');
		$this->webservice()->setRequestMethod('get');
		$this->webservice()->makeRequest();
		
		$item = new DOMDocument();
		$item->loadXML($this->webservice()->getResponse());
		$xpath = new DOMXPath($item);
		
		$xpath->query('item/identifier')->item(0)->nodeValue = $identifier;
		
		foreach ($properties as $name => $value) {
			$xpath->query('item/language/properties/property[@name="' . $name . '"]')->item(0)->nodeValue = $value;
		}
		
		$this->webservice()->setRequestPath('/items.ws');
		$this->webservice()->setRequestMethod('post');
		$this->webservice()->setPostData($item->saveXML());
		
		$this->webservice()->makeRequest();
		return $this->webservice()->getResponseCode() == 201;
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
