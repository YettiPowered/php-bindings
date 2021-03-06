<?php

namespace Yetti\API;

/**
 * Language model.
 * 
 * @author Sam Holman <sam@yetti.co.uk>
 * @copyright Copyright (c) 2011-2012, Yetti Ltd.
 * @package yetti-api
 */

class Language extends BaseAbstract
{
	/**
	 * Returns the language ID
	 * 
	 * @return int
	 */
	public function getId()
	{
		return (int)$this->getJson()->id;
	}
	
	/**
	 * Returns the name of this language
	 * 
	 * @return string
	 */
	public function getName()
	{
		return (string)$this->getJson()->name;
	}
	
	/**
	 * Returns the country code
	 * 
	 * @return string
	 */
	public function getCountryCode()
	{
		return (string)$this->getJson()->countryCode;
	}
	
	/**
	 * Returns whether or not this language is set as default
	 * 
	 * @return bool
	 */
	public function isDefault()
	{
		return (bool)$this->getJson()->default;
	}
}
