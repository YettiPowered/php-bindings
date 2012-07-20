<?php
require_once 'BaseAbstract.inc.php';

/**
 * API for interfacing with LabelEd property over web services.
 *
 * $Id$
 */

class LabelEdAPI_Property
{
	private
		$_json;
	
	public function __construct(stdClass $json)
	{
		$this->_json = $json;
	}
	
	/**
	 * Gets the data type of this property
	 * 
	 * @return string
	 */
	public function getDataType()
	{
		return (string)$this->_json->dataType; 
	}

	/**
	 * Gets the value of this property
	 * 
	 * @return string
	 */
	public function getValue()
	{
		return (string)$this->_json->value; 
	}
}
