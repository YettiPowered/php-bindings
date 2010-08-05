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
		$this->webservice()->setRequestPath('/items/' . $itemId . '.ws');
		$this->webservice()->setRequestMethod('get');
		$this->webservice()->makeRequest();
		
		$item = $this->webservice()->getResponseXmlObject();
		$identifier = $item->xpath('item/identifier');
		
		$itemArray = array('item' => array(
			'identifier' => (string)$identifier[0],
		));
		
		foreach ($item->xpath('item/language/properties/property') as $property) {
			$itemArray['properties'][(string)$property->attributes()->name] = (string)$property;
		}
		
		return $itemArray;
	}
}
