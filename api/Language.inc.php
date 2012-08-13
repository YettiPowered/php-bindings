<?php

/**
 * API for interfacing with Yetti languages over web services.
 * 
 * @author Sam Holman <sam@yetti.co.uk>
 * @copyright Copyright (c) 2011-2012, Yetti Ltd.
 */

class LabelEdAPI_Language extends LabelEdAPI_BaseAbstract
{
	/**
	 * Returns the language ID
	 * 
	 * @return int
	 */
	public function getId()
	{
		return $this->getJson()->id;
	}
	
	/**
	 * Returns the country code
	 * 
	 * @return string
	 */
	public function getCountryCode()
	{
		return $this->getJson()->countryCode;
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
