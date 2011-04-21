<?php
require_once 'Webservice.inc.php';
require_once 'Result.inc.php';

/**
 * API for interfacing with LabelEd items over web services.
 *
 * $Id$
 */

abstract class LabelEdAPI_BaseAbstract
{
	private
		$_webservice,
		$_xml;
	
	public function __construct()
	{
		$this->_webservice = new LabelEdAPI_WebService();
	}
	
	/**
	 * Returns the webservice object
	 *
	 * @return LabelEdAPI_WebService
	 */
	public function webservice()
	{
		return $this->_webservice;
	}
	
	/**
	 * Set the XML object
	 *
	 * @param SimpleXMLElement $xml
	 */
	protected function setXml(SimpleXMLElement $xml)
	{
		$this->_xml = $xml;
	}
	
	/**
	 * Returns the XML object
	 *
	 * @return SimpleXMLElement
	 */
	protected function getXml()
	{
		return $this->_xml;
	}
}
