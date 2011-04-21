<?php

/**
 * API for interfacing with LabelEd items over web services.
 *
 * $Id$
 */

class LabelEdAPI_Item extends LabelEdAPI_ResourceAbstract
{
	/**
	 * Loads an item by item ID or identifier
	 *
	 * @param mixed $itemId int ID or string identifier
	 * @return bool
	 */
	public function load($itemId)
	{
		$this->webservice()->setRequestPath('/items/' . $itemId . '.ws');
		$this->webservice()->setRequestMethod('get');
		
		if ($this->webservice()->makeRequest())
		{
			$this->setXml($this->webservice()->getResponseXmlObject());
			return true;
		}
		
		return false;
	}
	
	protected function loadTemplate()
	{
		$this->webservice()->setRequestPath('/templates/item/' . $this->getTypeId() . '.ws');
		$this->webservice()->setRequestMethod('get');
		
		if ($this->webservice()->makeRequest())
		{
			$this->setXml($this->webservice()->getResponseXmlObject());
			return true;
		}
		
		return false;
	}
	
	protected function create()
	{
		$this->webservice()->setRequestPath('/items.ws');
		$this->webservice()->setRequestMethod('post');
		
		$this->getXml()->item->identifier = $this->getName();
		
		// Properties
		foreach ($this->getXml()->item->language->properties->property as $property) {
			$property[0] = $this->getPropertyValue($property->attributes()->name);
		}
		
		// Meta info
		$this->getXml()->item->language->metaInfo->title 		= $this->getMetaTitle();
		$this->getXml()->item->language->metaInfo->description 	= $this->getMetaDescription();
		$this->getXml()->item->language->metaInfo->keywords		= $this->getMetaKeywords();
		
		// Product options
		$this->getXml()->item->language->productOptions->shippingUnitValue	= $this->getShippingUnitValue();
		$this->getXml()->item->language->productOptions->vatBandId 			= $this->getVatBandId();
		
		// Categories
		
		// Revision comment
		$this->getXml()->item->language->revisionComment = $this->getRevisionComment();
		
		$this->webservice()->setPostData($this->getXml()->asXML());
		return $this->makeRequestReturnResult();
	}
	
	protected function update()
	{
		
	}
}
