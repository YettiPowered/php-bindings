<?php

namespace Yetti\API;

/**
 * Item combination model.
 * 
 * @author Sam Holman <sam@yetti.co.uk>
 * @copyright Copyright (c) 2012, Yetti Ltd.
 * @package yetti-api
 */

class Item_Combination extends BaseAbstract
{
	/**
	 * Load a combination by ID
	 * 
	 * @param int $combinationId
	 * @return void
	 */
	public function load($combinationId)
	{
		$this->webservice()->setRequestPath('/items/combinations/null/' . $combinationId . '.ws');
		$this->webservice()->setRequestMethod('get');
		
		if ($this->webservice()->makeRequest())
		{
			$this->setJson($this->webservice()->getResponseJsonObject()->combination);
			return true;
		}
		
		return false;
	}
	
	/**
	 * Save this combination
	 * 
	 * @return \Yetti\API\Result
	 */
	public function save()
	{
		$this->webservice()->setRequestPath('/items/combinations.ws');
		$this->webservice()->setRequestMethod('put');
		$this->webservice()->setRequestParam('combinationId', $this->getId());
		
		$this->webservice()->setPostData(json_encode($this->getJson()));
		return $this->makeRequestReturnResult();
	}
	
	/**
	 * Returns the combination ID
	 * 
	 * @return int
	 */
	public function getId()
	{
		return (int)$this->getJson()->id;
	}
	
	/**
	 * Set whether or not this combination is available for purchase
	 * 
	 * @param bool $available
	 * @return void
	 */
	public function setAvailable($available=true)
	{
		$this->getJson()->available = (bool)$available;
	}
	
	/**
	 * Returns whether or not this combination is marked as available for purchase.
	 * 
	 * @return bool
	 */
	public function isAvailable()
	{
		return (bool)$this->getJson()->available;
	}
	
	/**
	 * Set the stock level
	 * 
	 * @param int $stock
	 * @return void
	 */
	public function setStock($stock)
	{
		if (is_numeric($stock)) {
			$this->getJson()->stock = (int)$stock;
		}
	}
	
	/**
	 * Returns this combination's stock level
	 * 
	 * @return int
	 */
	public function getStock()
	{
		return (int)$this->getJson()->stock;
	}
	
	/**
	 * Set the SKU
	 * 
	 * @param string $sku
	 * @return void
	 */
	public function setSku($sku)
	{
		if (is_string($sku)) {
			$this->getJson()->sku = $sku;
		}
	}
	
	/**
	 * Returns this combination's SKU
	 * 
	 * @return string
	 */
	public function getSku()
	{
		return $this->getJson()->sku;
	}
	
	/**
	 * Returns this combination's price
	 * 
	 * @return float
	 */
	public function getPrice()
	{
		return (float)$this->getJson()->price;
	}
	
	/**
	 * Returns this combination's options
	 * 
	 * @var array
	 */
	public function getOptions()
	{
		$options = array();
		
		foreach ($this->getJson()->options as $optionData) {
			$options += get_object_vars($optionData);
		}
		
		return $options;
	}
}
