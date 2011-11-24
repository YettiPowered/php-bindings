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
		$_xml;
	
	public function __construct(SimpleXMLElement $xml)
	{
		$this->_xml = $xml;
	}
	
	/**
	 * Gets the data type of this property
	 * 
	 * @return string
	 */
	public function getDataType()
	{
		return (string)$this->_xml->dataType; 
	}

	/**
	 * Gets the value of this property
	 * 
	 * @return string
	 */
	public function getValue()
	{
		return (string)$this->_xml->value; 
	}
}
