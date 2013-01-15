<?php

namespace Yetti\API;

/**
 * Item variation model.
 * 
 * @author Sam Holman <sam@yetti.co.uk>
 * @copyright Copyright (c) 2012, Yetti Ltd.
 * @package yetti-api
 */

class Item_Variation
{
	const
		/**
		 * Pricing modifier constants
		 */
		PRICING_SAME 		 = 0,
		PRICING_FIXED 		 = 1,
		PRICING_PERCENT_DIFF = 2,
		PRICING_FIXED_DIFF 	 = 3;
	
	private
		/**
		 * The variation's ID
		 * 
		 * @var int
		 */
		$_id,
	
		/**
		 * The variation name
		 * 
		 * @var string
		 */
		$_name,
		
		/**
		 * An array of variation options
		 * 
		 * @var array
		 */
		$_options = array();
	
	/**
	 * Set this variation's ID
	 * 
	 * @param int $id
	 * @return void
	 */
	public function setId($id)
	{
		if (is_numeric($id)) {
			$this->_id = (int)$id;
		}
	}
	
	/**
	 * Returns this variation's ID
	 * 
	 * @return int
	 */
	public function getId()
	{
		return (int)$this->_id;
	}
	
	/**
	 * Set this variation's name
	 * 
	 * @param string $name
	 * @return void
	 */
	public function setName($name)
	{
		if (is_string($name)) {
			$this->_name = $name;
		}
	}
	
	/**
	 * Returns the variation name
	 * 
	 * @return string
	 */
	public function getName()
	{
		return $this->_name;
	}
	
	/**
	 * Add an option to this variation
	 * 
	 * @param string $name
	 * @param float $price
	 * @param int $pricing
	 * @return void
	 */
	public function addOption($name, $price=0, $pricing=self::PRICING_SAME)
	{
		if (is_string($name))
		{
			$this->_options[] = array(
				'name'	  => $name,
				'price'	  => (float)$price,
				'pricing' => (int)$pricing,
			);
		}
	}
	
	/**
	 * Returns an array of options for this variation
	 * 
	 * @return array
	 */
	public function getOptions()
	{
		return $this->_options;
	}
	
	/**
	 * Present this object as an array
	 * 
	 * @return array
	 */
	public function getAsArray()
	{
		return array(
			'id' 	  => $this->_id,
			'name' 	  => $this->_name,
			'options' => $this->_options,
		);
	}
}
