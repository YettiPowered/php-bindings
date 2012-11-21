<?php

namespace Yetti\API;

/**
 * Model represents a single property (of a resource)
 * 
 * @author Sam Holman <sam@yetti.co.uk>
 * @copyright Copyright (c) 2011-2012, Yetti Ltd.
 * @package yetti-api
 */

class Resource_Property extends BaseAbstract
{
	/**
	 * Returns the property ID
	 * 
	 * @return int
	 */
	public function getId()
	{
		return (int)$this->getJson()->id;
	}
	
	/**
	 * Returns the data type of this property
	 * 
	 * @return string
	 */
	public function getDataType()
	{
		return (string)$this->getJson()->dataType;
	}

	/**
	 * Returns the value of this property
	 * 
	 * @return string
	 */
	public function getValue()
	{
		return (string)$this->getJson()->value;
	}
}
