<?php
require_once 'ResourceAbstract.inc.php';

/**
 * API for interfacing with LabelEd users over web services.
 *
 * $Id$
 */

class LabelEdAPI_User extends LabelEdAPI_ResourceAbstract
{
	/**
	 * Loads an item by item ID or identifier
	 *
	 * @param mixed $itemId int ID or string identifier
	 * @return bool
	 */
	public function load($resourceId)
	{
		$this->webservice()->setRequestPath('/users/' . $resourceId . '.ws');
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
		$this->webservice()->setRequestPath('/templates/user.ws');
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
		$this->webservice()->setRequestPath('/users.ws');
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
		$this->webservice()->setRequestPath('/users.ws');
		$this->webservice()->setRequestParam('resourceId', $this->getId());
		$this->webservice()->setRequestMethod('put');
		
		$this->webservice()->setPostData($this->getXml()->asXML());
		return $this->makeRequestReturnResult();
	}
	
	/**
	 * Returns the hashed password for this user
	 * 
	 * @return string
	 */
	public function getPassHash()
	{
		return (string)$this->getXml()->item->resource->password;
	}
}
