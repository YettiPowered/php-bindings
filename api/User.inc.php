<?php
require_once 'ResourceAbstract.inc.php';

/**
 * API for interfacing with LabelEd users over web services.
 *
 * $Id$
 */

class User extends ResourceAbstract
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
		$this->webservice()->setRequestPath('/templates/user.ws');
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
		$this->webservice()->setRequestPath('/users.ws');
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
		$this->webservice()->setRequestPath('/users.ws');
		$this->webservice()->setRequestParam('resourceId', $this->getId());
		$this->webservice()->setRequestMethod('put');
		
		$this->webservice()->setPostData($this->getJson()->asXML());
		return $this->makeRequestReturnResult();
	}
	
	/**
	 * Returns the hashed password for this user
	 * 
	 * @return string
	 */
	public function getPassHash()
	{
		return (string)$this->getJson()->item->resource->password;
	}
	
	/**
	* Returns the email address for this user
	*
	* @return string
	*/
	public function getEmail()
	{
		return (string)$this->getJson()->item->resource->email;
	}
}
