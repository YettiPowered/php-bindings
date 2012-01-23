<?php
require_once 'ResourceAbstract.inc.php';

/**
 * API for interfacing with LabelEd collections over web services.
 *
 * $Id$
 */

class LabelEdAPI_Collection extends LabelEdAPI_ResourceAbstract
{
	/**
	 * Loads an item by collection ID or identifier
	 *
	 * @param mixed $resourceId int ID or string identifier
	 * @return bool
	 */
	public function load($resourceId)
	{
		$this->webservice()->setRequestPath('/collections' . '.ws');
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
		$this->webservice()->setRequestPath('/templates/collection/' . ((int)$typeId) . '.ws');
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
		$this->webservice()->setRequestPath('/collections.ws');
		$this->webservice()->setRequestMethod('post');
		
		$this->webservice()->setPostData($this->getXml()->asXML());
		return $this->makeRequestReturnResult();
	}
	
	/**
	 * (non-PHPdoc)
	 * @see LabelEdAPI_ResourceAbstract::update()
	 */
	public function update()
	{
		$this->webservice()->setRequestPath('/collections.ws');
		$this->webservice()->setRequestParam('resourceId', $this->getId());
		$this->webservice()->setRequestMethod('put');
		
		$this->webservice()->setPostData($this->getXml()->asXML());
		return $this->makeRequestReturnResult();
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
	
	/**
	 * Sets the parentId for the collection
	 * 
	 * @param $parentId
	 */
	public function setParentId($parentId)
	{
		$this->getXml()->item->parentId = (int)$parentId;
	}
}
