<?php

/**
 * API for interfacing with LabelEd items over web services.
 *
 * $Id$
 *
 */
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
	 * Returns a template for use when creating a new item
	 *
	 * @param int $typeId
	 * @return array
	 */
	public function template($typeId)
	{
		$itemArray = array();
		
		$this->webservice()->setRequestPath('/templates/item/' . $typeId . '.ws');
		$this->webservice()->setRequestMethod('get');
		
		if ($this->webservice()->makeRequest())
		{
			$template = $this->webservice()->getResponseXmlObject();
							
			$itemArray = array('item' => array(
				'identifier'	=> '',
				'typeId' 		=> $typeId,
			));
			
			foreach ($template->xpath('item/language/properties/property') as $property) {
				$itemArray['properties'][(string)$property->attributes()->name] = '';
			}
		}
		
		return $itemArray;
	}
	
	/**
	 * Create a new item
	 *
	 * @param array $itemArray
	 * @return LabelEdAPIResult
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
		
		foreach ($properties as $name => $value)
		{
			$xpath->query('item/language/properties/property[@name="' . $name . '"]')->item(0)->nodeValue = '';
			$xpath->query('item/language/properties/property[@name="' . $name . '"]')->item(0)->appendChild($item->createCDATASection($value));
		}
		
		if (!empty($itemArray['revisionComment'])) {
			$xpath->query('item/language/revisionComment')->item(0)->appendChild($item->createCDATASection($itemArray['revisionComment']));
		}
		
		$this->webservice()->setRequestPath('/items.ws');
		$this->webservice()->setRequestMethod('post');
		$this->webservice()->setPostData($item->saveXML());
		
		return $this->makeRequestReturnResult();
	}
	
	/**
	 * Update an existing item
	 *
	 * @param int $itemId
	 * @param array $itemArray
	 * @return LabelEdAPIResult
	 */
	public function update($itemId, $itemArray)
	{
		$identifier = !empty($itemArray['item']['identifier']) ? $itemArray['item']['identifier'] : false;
		$properties = !empty($itemArray['properties']) && is_array($itemArray['properties']) ?
			$itemArray['properties'] : false;
		
		$itemPath = '/items/' . $itemId . '.ws';
		
		$this->webservice()->setRequestPath($itemPath);
		$this->webservice()->setRequestMethod('get');
		$this->webservice()->makeRequest();
		
		$item = new DOMDocument();
		$item->loadXML($this->webservice()->getResponse());
		$xpath = new DOMXPath($item);
		
		$xpath->query('item/identifier')->item(0)->nodeValue = $identifier;
		
		foreach ($properties as $name => $value)
		{
			$xpath->query('item/language/properties/property[@name="' . $name . '"]')->item(0)->nodeValue = '';
			$xpath->query('item/language/properties/property[@name="' . $name . '"]')->item(0)->appendChild($item->createCDATASection($value['value']));
		}
		
		if (!empty($itemArray['revisionComment'])) {
			$xpath->query('item/language/revisionComment')->item(0)->appendChild($item->createCDATASection($itemArray['revisionComment']));
		}
		
		$this->webservice()->setRequestPath($itemPath);
		$this->webservice()->setRequestMethod('put');
		$this->webservice()->setPostData($item->saveXML());
		
		return $this->makeRequestReturnResult();
	}
	
	/**
	 * Performs a webservice request and returns a result object
	 * Must be called after everything else has been set up
	 *
	 * @return LabelEdAPIResult
	 */
	private function makeRequestReturnResult()
	{
		$this->webservice()->makeRequest();
		$result = new LabelEdAPIResult();
		
		if (substr($this->webservice()->getResponseCode(), 0, 1) != 2)
		{
			$response = $this->webservice()->getResponseXmlObject();
			
			if (isset($response->response) && isset($response->response->errors))
			{
				foreach ($response->response->errors->error as $error) {
					$result->addError($error);
				}
			}
		}
		
		return $result;
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
			
			$items = array();
			
			foreach ($this->webservice()->getResponseXmlObject()->items->item as $item)
			{
				$items[] = array(
					'id'			=> (int)$item->attributes()->id,
					'typeId' 		=> (int)$item->attributes()->typeId,
					'identifier'	=> (string)$item->identifier,
				);
			}
			
			$this->_items = $items;
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
			
			if (is_object($item) && $item instanceof SimpleXMLElement)
			{
				if ($identifier = $item->xpath('item/identifier'))
				{
					$itemElement = $item->xpath('item');
					
					$itemArray = array('item' => array(
						'identifier'	=> (string)$identifier[0],
						'typeId' 		=> (string)$itemElement[0]->attributes()->typeId,
					));
					
					foreach ($item->xpath('item/language/properties/property') as $property)
					{
						$itemArray['properties'][(string)$property->attributes()->name] = array(
							'value'		=> (string)$property,
							'dataType'	=> (string)$property->attributes()->dataType,
							'required'	=> (string)$property->attributes()->required,
						);
					}
				}
			}
		}
		
		return $itemArray;
	}
}
