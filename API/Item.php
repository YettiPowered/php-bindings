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
	private
		/**
		 * Whether or not variations have been parsed into objects from JSON yet.
		 * 
		 * @var bool
		 */
		$_parsedVariations = false,
	
		/**
		 * An array of new variation objects
		 * 
		 * @var array
		 */
		$_variations = array();
	
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
	 * Perform pre save actions
	 * 
	 * @return void
	 */
	protected function _beforeSave()
	{
		$this->processVariations();
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
	 * Process variations from the internal array into JSON data
	 * 
	 * @return void
	 */
	private function processVariations()
	{
		$existingVariations = $this->getVariations();
		$this->getJson()->variations = array();
		
		foreach ($existingVariations as $variation) {
			$this->getJson()->variations[] = $variation->getAsArray();
		}
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
	 * Set the VAT band ID
	 * 
	 * @param int $bandId
	 * @return void
	 */
	public function setVatBandId($bandId)
	{
		if (!isset($this->getJson()->pricing))
		{
			$this->getJson()->pricing 	   = new \stdClass();
			$this->getJson()->pricing->vat = new \stdClass();
		}
		
		$this->getJson()->pricing->vat->bandId = $bandId;
	}
	
	/**
	 * Returns the VAT band ID
	 * 
	 * @return int
	 */
	public function getVatBandId()
	{
		return (int)$this->getJson()->pricing->vat->bandId;
	}
	
	/**
	 * Set the product weight
	 * 
	 * @param float $weight
	 * @return void
	 */
	public function setWeight($weight)
	{
		$this->getJson()->weight = $weight;
	}
	
	/**
	 * Returns the product weight
	 * 
	 * @return float
	 */
	public function getWeight()
	{
		return $this->getJson()->weight;
	}
	
	/**
	 * Clear all existing pricing tiers
	 * 
	 * @return void
	 */
	public function clearPricingTiers()
	{
		$this->getJson()->pricingTiers = array();
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
	 * Clear existing variations
	 * 
	 * @return void
	 */
	public function clearVariations()
	{
		$this->getJson()->variations = $this->_variations = array();
	}
	
	/**
	 * Add a new product variation to this item
	 * 
	 * @param string $variationName
	 * @return \Yetti\API\Item_Variation
	 */
	public function addVariation($variationName)
	{
		$variation = new \Yetti\API\Item_Variation();
		$variation->setName($variationName);
		
		return $this->_variations[] = $variation;
	}
	
	/**
	 * Returns an array of variation objects for this item
	 * 
	 * @return array
	 */
	public function getVariations()
	{
		if (!$this->_parsedVariations)
		{
			$variations = isset($this->getJson()->variations) ? $this->getJson()->variations : null;
			
			if (is_array($variations))
			{
				foreach ($variations as $variationData)
				{
					$variationId   = isset($variationData->id) ? $variationData->id : null;
					$variationName = isset($variationData->name) ? $variationData->name : null;
					$options 	   = isset($variationData->options) ? $variationData->options : null;
					
					if (!empty($variationName))
					{
						$variation = $this->addVariation($variationName);
						$variation->setId($variationId);
						
						if (is_array($options))
						{
							foreach ($options as $optionData)
							{
								$name	 = isset($optionData->name) ? $optionData->name : null;
								$price	 = isset($optionData->price) ? $optionData->price : null;
								$pricing = isset($optionData->pricing) ? $optionData->pricing : null;
								
								if (!empty($name)) {
									$variation->addOption($name, $price, $pricing);
								}
							}
						}
					}
				}
			}
			
			$this->_parsedVariations = true;
		}
		
		return $this->_variations;
	}
	
	/**
	 * Sets the file content to be sent for saving
	 * 
	 * Example usage:
	 * 	$item->setFileData(file_get_contents($filepath));
	 * 
	 * @param string $fileData
	 * @return void
	 */
	public function setFileData($fileData)
	{
		if (is_string($fileData)) {
			$this->getJson()->fileData = base64_encode($fileData);
		}
	}
}
