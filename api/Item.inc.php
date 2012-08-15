<?php

namespace Yetti\API;

require_once 'ResourceAbstract.inc.php';

/**
 * API for interfacing with Yetti items over web services.
 * 
 * @author Sam Holman <sam@yetti.co.uk>
 * @copyright Copyright (c) 2011-2012, Yetti Ltd.
 */

class Item extends ResourceAbstract
{
	private
		$_countryCode;
	
	/**
	 * Loads an item by item ID or identifier
	 *
	 * @param mixed $itemId int ID or string identifier
	 * @param string $countryCode
	 * @return bool
	 */
	public function load($resourceId, $countryCode=null)
	{
		if ($countryCode && is_string($countryCode)) {
			$this->_countryCode = '/' . strtolower($countryCode);
		}
		
		$requestPath = $this->_countryCode . '/items/fake/' . $resourceId . '.ws';
		
		$this->webservice()->setRequestPath($requestPath);
		$this->webservice()->setRequestMethod('get');
		
		if ($this->webservice()->makeRequest())
		{
			$this->setJson($this->webservice()->getResponseJsonObject()->item);
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
		$this->webservice()->setRequestPath('/templates/item/' . ((int)$typeId) . '.ws');
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
		$this->webservice()->setRequestPath('/items.ws');
		$this->webservice()->setRequestMethod('post');
		
		$this->webservice()->setPostData(json_encode($this->getJson()));
		return $this->makeRequestReturnResult();
	}
	
	/**
	 * (non-PHPdoc)
	 * @see ResourceAbstract::update()
	 */
	public function update()
	{
		$this->webservice()->setRequestPath($this->_countryCode . '/items.ws');
		$this->webservice()->setRequestParam('typeId', $this->getTypeId());
		$this->webservice()->setRequestParam('resourceId', $this->getId());
		$this->webservice()->setRequestMethod('put');
		
		$this->webservice()->setPostData(json_encode(array('item' => $this->getJson())));
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
		
		foreach ($this->getJson()->item->collectionIds as $itemId) {
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
		return (string)$this->getJson()->item->resource->name;
	}
	
	/**
	 * Adds a new pricing tier to this item
	 * 
	 * @param float $price
	 * @param int $appliesToId
	 * @param int $appliesToIdType
	 */
	public function addPricingTier($price, $appliesToId=-1, $appliesToIdType=100)
	{
		$tier = $this->getJson()->item->addChild('pricingTiers');
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
		$this->getJson()->item->fileData = base64_encode($data);
	}
}