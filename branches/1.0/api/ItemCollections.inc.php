<?php
require_once 'BaseAbstract.inc.php';

/**
 * API for interfacing with LabelEd collection items
 * $Id$
 */

class LabelEdAPI_ItemCollections extends LabelEdAPI_BaseAbstract
{
	/**
	 * Loads an items collections
	 *
	 * @param mixed $resourceId int ID or string identifier
	 * @return bool
	 */
	public function load($resourceId)
	{
		$this->webservice()->setRequestPath('/items/collections' . '.ws');
		$this->webservice()->setRequestParam('itemId', $resourceId);
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
	 * @see LabelEdAPI_ResourceAbstract::update()
	 */
	public function update()
	{
		/*$this->webservice()->setRequestPath('/items.ws');
		$this->webservice()->setRequestParam('resourceId', $this->getId());
		$this->webservice()->setRequestMethod('put');
		
		$this->webservice()->setPostData($this->getXml()->asXML());
		return $this->makeRequestReturnResult();*/
	}
}
