<?php

namespace Yetti\API;

/**
 * Individual item model
 * 
 * @author Sam Holman <sam@yetti.co.uk>
 * @copyright Copyright (c) 2011-2012, Yetti Ltd.
 * @package yetti-api
 */

class Item extends Resource_BaseAbstract
{
	/**
	 * Returns a singular name for this type of resource
	 * 
	 * @return string
	 */
	protected function getSingularName()
	{
		return 'item';
	}
	
	/**
	 * Returns an array of collection IDs that the loaded item is assigned to
	 * 
	 * @return array
	 */
	public function getCollectionIds()
	{
		$return = array();
		
		foreach ($this->getJson()->collectionIds as $itemId) {
			$return[] = (int)$itemId;
		}
		
		return $return;
	}
	
	/**
	 * Adds a new pricing tier to this item
	 * 
	 * @param float $price
	 * @param int $appliesToId
	 * @param int $appliesToIdType
	 * @return void
	 */
	public function addPricingTier($price, $appliesToId=-1, $appliesToIdType=100)
	{
		$this->getJson()->pricingTiers[] = array
		(
			'price' 		  => $price,
			'appliesToId'	  => $appliesToId,
			'appliesToIdType' => $appliesToIdType,
		);
	}
	
	/**
	 * Sets the file content to be sent for saving
	 * 
	 * @param string $data
	 * @return void
	 */
	public function setFileData($data)
	{
		$this->getJson()->fileData = base64_encode($data);
	}
}
