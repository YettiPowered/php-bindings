<?php
require_once 'ResourceAbstract.inc.php';

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
	public function load($resourceId)
	{
		$this->webservice()->setRequestPath('/items' . '.ws');
		$this->webservice()->setRequestParam('resourceId', $resourceId);
		$this->webservice()->setRequestMethod('get');
		
		if ($this->webservice()->makeRequest())
		{
			$this->setXml($this->webservice()->getResponseXmlObject());
			return true;
		}
		
		return false;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see LabelEdAPI_ResourceAbstract::loadTemplate()
	 */
	public function loadTemplate($typeId=null)
	{
		$this->webservice()->setRequestPath('/templates/item/' . ((int)$typeId) . '.ws');
		$this->webservice()->setRequestMethod('get');
		
		if ($this->webservice()->makeRequest())
		{
			$this->setXml($this->webservice()->getResponseXmlObject());
			return true;
		}
		
		return false;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see LabelEdAPI_ResourceAbstract::create()
	 */
	public function create()
	{
		$this->loadTemplate();
		$this->webservice()->setRequestPath('/items.ws');
		$this->webservice()->setRequestMethod('post');
		
		$this->getXml()->resource->resource->identifier = $this->getName();
		
		// Properties
		foreach ($this->getXml()->resource->properties->children() as $property) {
			$property->value = $this->getPropertyValue($property->getName());
		}
				
		// Categories
		
		// Revision comment
		$this->getXml()->resource->revision->comment = $this->getRevisionComment();
				
		$this->webservice()->setPostData($this->getXml()->asXML());
		return $this->makeRequestReturnResult();
	}
	
	/**
	 * (non-PHPdoc)
	 * @see LabelEdAPI_ResourceAbstract::update()
	 */
	public function update()
	{
		throw new Exception('Not yet implemented');
	}
	
	/**
	 * Returns the item display name
	 *
	 * @return string
	 */
	public function getDisplayName()
	{
		return (string)$this->getXml()->item->resource->name;
	}
	
	public function getMetaTitle()
	{
		throw new Exception('Not yet implemented');
	}
	
	public function getMetaDescription()
	{
		throw new Exception('Not yet implemented');
	}
	
	public function getMetaKeywords()
	{
		throw new Exception('Not yet implemented');
	}
	
	public function getShippingUnitValue()
	{
		throw new Exception('Not yet implemented');
	}
	
	public function getVatBandId()
	{
		throw new Exception('Not yet implemented');
	}
}
