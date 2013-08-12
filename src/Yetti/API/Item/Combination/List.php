<?php

namespace Yetti\API;

/**
 * Item combinations list model
 * Responsible for listing combinations for a product item.
 *
 * @author Sam Holman <sam@yetti.co.uk>
 * @copyright Copyright (c) 2012, Yetti Ltd.
 * @package yetti-api
 */

class Item_Combination_List extends Resource_ListAbstract
{
	/**
	 * Construct a new item combination list model
	 * 
	 * @return void
	 */
	public function __construct()
	{
		$this->setPath('items/combinations');
	}
	
	/**
	 * Save info related to this combination set
	 * 
	 * @return \Yetti\API\Result
	 */
	public function save()
	{
		$this->webservice()->setRequestPath('/' . $this->getPath() . '/' . $this->getItemId() .  '.ws');
		$this->webservice()->setRequestMethod('put');
		
		$this->webservice()->setPostData(json_encode($this->getJson()));
		return $this->makeRequestReturnResult();
	}
	
	/**
	 * Returns the item ID for this combination set
	 * 
	 * @return int
	 */
	public function getItemId()
	{
		return $this->getTypeId();
	}
	
	/**
	 * Set whether or not stock control is being used
	 * 
	 * @param bool $usingStockControl
	 * @return void
	 */
	public function setUsingStockControl($usingStockControl=true)
	{
		$this->getJson()->useStockControl = (bool)$usingStockControl;
	}
	
	/**
	 * Returns whether or not stock control is enabled for this item combination set
	 * 
	 * @return bool
	 */
	public function isUsingStockControl()
	{
		return (bool)$this->getJson()->useStockControl;
	}
	
	/**
	 * Returns a new item combination object
	 * 
	 * @return object \Yetti\API\Item_Combination
	 */
	protected function getNewItemObject()
	{
		return new \Yetti\API\Item_Combination();
	}
}
