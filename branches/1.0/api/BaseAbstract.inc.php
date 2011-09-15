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
	
	public function __construct() {}
	
	/**
	 * Returns the webservice object
	 *
	 * @return LabelEdAPI_WebService
	 */
	public function webservice()
	{
		if (!$this->_webservice) {
			$this->_webservice = new LabelEdAPI_WebService();
		}
		return $this->_webservice;
	}
	
	/**
	 * Set the XML object
	 *
	 * @param SimpleXMLElement $xml
	 */
	public function setXml(SimpleXMLElement $xml)
	{
		$this->_xml = $xml;
	}
	
	/**
	 * Returns the XML object
	 * @throws Exception
	 * @return SimpleXMLElement
	 */
	public function getXml()
	{
		if (!isset($this->_xml)) {
			throw new Exception('Unable to access XML data without first loading resource or template');
		}
		
		return $this->_xml;
	}
}
