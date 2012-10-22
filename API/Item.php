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
	 * Perform post save actions
	 * 
	 * @return void
	 */
	protected function _afterSave()
	{
		$this->saveCollections();
	}
	
	/**
	 * Save the list of associated collections for this item
	 * 
	 * @return void
	 */
	private function saveCollections()
	{
		$itemCollections = new \Yetti\API\Item_Collections();
		$itemCollections->load($this->getId());
		$itemCollections->clearCollections();
		
		foreach ($this->getCollectionIds() as $collectionId) {
			$itemCollections->addCollection($collectionId);
		}
		
		$itemCollections->save();
	}
	
	/**
	 * Assign this item to the collection with the given ID
	 * 
	 * @param int $collectionId
	 * @return void
	 */
	public function assignToCollection($collectionId)
	{
		$this->getJson()->collectionIds[] = (int)$collectionId;
	}
	
	/**
	 * Returns an array of collection IDs that the loaded item is assigned to
	 * 
	 * @return array
	 */
	public function getCollectionIds()
	{
		$return = array();
		
		foreach ($this->getJson()->collectionIds as $collectionId) {
			$return[] = (int)$collectionId;
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
	public function addPricingTier($price, $appliesToId=-1, $appliesToIdType=Resource_BaseAbstract::ID_TYPE_ALL)
	{
		$newElement = array(
			'price' 		  		  => $price,
			'appliesToResourceId'	  => $appliesToId,
			'appliesToResourceIdType' => $appliesToIdType,
		);
		
		$emptyFirstTier = isset($this->getJson()->pricingTiers[0]) && 
			$this->getJson()->pricingTiers[0]->price == 0 && 
			$this->getJson()->pricingTiers[0]->appliesToResourceId == -1 && 
			$this->getJson()->pricingTiers[0]->appliesToResourceIdType == 100;
		
		if ($emptyFirstTier) {
			$this->getJson()->pricingTiers[0] = $newElement;
		}
		else {
			$this->getJson()->pricingTiers[] = $newElement;
		}
	}
	
	/**
	 * Returns the price of this item (if applicable).
	 * 
	 * @param int $appliesToId
	 * @param int $appliesToIdType
	 * @return float
	 */
	public function getPrice($appliesToId=-1, $appliesToIdType=Resource_BaseAbstract::ID_TYPE_ALL)
	{
		foreach ($this->getJson()->pricingTiers as $tier)
		{
			$tier = (array)$tier;
			
			if ($tier['appliesToResourceId'] == $appliesToId && $tier['appliesToResourceIdType'] == $appliesToIdType) {
				return (float)$tier['price'];
			}
		}
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
