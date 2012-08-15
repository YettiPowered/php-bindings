<?php
require_once 'ResourceAbstract.inc.php';

/**
 * API for interfacing with LabelEd collections over web services.
 *
 * $Id$
 */

class Collection extends ResourceAbstract
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
			$this->setJson($this->webservice()->getResponseJsonObject());
			return true;
		}
		
		return false;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see ResourceAbstract::loadTemplate()
	 */
	public function loadTemplate($typeId=null)
	{
		$this->webservice()->setRequestPath('/templates/collection/' . ((int)$typeId) . '.ws');
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
	 * @see ResourceAbstract::create()
	 */
	public function create()
	{
		$this->webservice()->setRequestPath('/collections.ws');
		$this->webservice()->setRequestMethod('post');
		
		$this->webservice()->setPostData($this->getJson()->asXML());
		return $this->makeRequestReturnResult();
	}
	
	/**
	 * (non-PHPdoc)
	 * @see ResourceAbstract::update()
	 */
	public function update()
	{
		$this->webservice()->setRequestPath('/collections.ws');
		$this->webservice()->setRequestParam('resourceId', $this->getId());
		$this->webservice()->setRequestMethod('put');
		
		$this->webservice()->setPostData($this->getJson()->asXML());
		return $this->makeRequestReturnResult();
	}
	
	/**
	 * Returns the item display name
	 *
	 * @return string
	 */
	public function getDisplayName()
	{
		return (string)$this->getJson()->item->resource->name;
	}
	
	/**
	 * Sets the parentId for the collection
	 * 
	 * @param $parentId
	 */
	public function setParentId($parentId)
	{
		$this->getJson()->item->parentId = (int)$parentId;
	}
}
