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
		$this->webservice()->setRequestPath('/items.ws');
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
		$this->webservice()->setRequestPath('/items.ws');
		$this->webservice()->setRequestParam('resourceId', $this->getId());
		$this->webservice()->setRequestMethod('put');
		
		$this->webservice()->setPostData($this->getXml()->asXML());
		return $this->makeRequestReturnResult();
	}
	
	/**
	 * Returns an array of collection Ids that the item is in
	 * 
	 * @return array
	 */
	public function getCollectionIds()
	{
		$return = array();
		
		foreach ($this->getXml()->item->collectionIds as $itemId) {
			$return[] = (int)((string)$itemId);
		}
		
		return $return;
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
	 * Adds a new pricing teir to this item
	 * 
	 * @param float $price
	 * @param int $appliesToId
	 * @param int $appliesToIdType
	 */
	public function addPricingTier($price, $appliesToId=-1, $appliesToIdType=100)
	{
		$tier = $this->getXml()->item->addChild('pricingTiers');
		$tier->addChild('price', $price);
		$tier->addChild('appliesToId', $appliesToId);
		$tier->addChild('appliesToIdType', $appliesToIdType);
	}
	
	/**
	 * Sets the file content to be send for saving
	 * 
	 * @param string $data
	 * @return void;
	 */
	public function setFileData($data)
	{
		$this->getXml()->item->fileData = base64_encode($data);
	}
}
