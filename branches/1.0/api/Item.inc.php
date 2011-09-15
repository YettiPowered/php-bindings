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
			echo $this->webservice()->getResponse();
			$this->setXml($this->webservice()->getResponseXmlObject());
			return true;
		}
		echo $this->webservice()->getResponse();
		
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
	
	protected function update()
	{
		
	}
}
