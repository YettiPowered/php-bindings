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
	
	
	/**
	 * Performs a webservice request and returns a result object
	 * Must be called after everything else has been set up
	 *
	 * @return LabelEdAPI_Result
	 */
	protected function makeRequestReturnResult()
	{
		$this->webservice()->makeRequest();
		$result = new LabelEdAPI_Result();
		
		if (substr($this->webservice()->getResponseCode(), 0, 1) != 2)
		{
			$response = $this->webservice()->getResponseXmlObject();
			
			if (isset($response) && isset($response->errors))
			{
				foreach ($response->errors->error as $error) {
					$result->addError((string)$error->error, (string)$error->key);
				}
			}
		}
		
		return $result;
	}
}
