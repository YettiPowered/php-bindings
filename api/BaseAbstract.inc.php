<?php

require_once 'Webservice.inc.php';
require_once 'Result.inc.php';

/**
 * Abstract base class for Yetti API bindings
 *
 * @author Sam Holman <sam@yetti.co.uk>
 * @copyright Copyright (c) 2011-2012, Yetti Ltd.
 */

abstract class BaseAbstract
{
	private
		$_webservice,
		$_json;
	
	public function __construct() {}
	
	/**
	 * Returns the webservice object
	 *
	 * @return WebService
	 */
	public function webservice()
	{
		if (!$this->_webservice) {
			$this->_webservice = new WebService();
		}
		
		return $this->_webservice;
	}
	
	/**
	 * Set the JSON object
	 *
	 * @param stdClass $json
	 */
	public function setJson($json)
	{
		$this->_json = $json;
	}
	
	/**
	 * Returns the JSON object
	 * 
	 * @throws Exception
	 * @return stdClass
	 */
	public function getJson()
	{
		if (!isset($this->_json)) {
			throw new Exception('Unable to access JSON data without first loading resource or template');
		}
		
		return $this->_json;
	}
	
	/**
	 * Performs a webservice request and returns a result object
	 * Must be called after everything else has been set up
	 *
	 * @return Result
	 */
	protected function makeRequestReturnResult()
	{
		$this->webservice()->makeRequest();
		$result = new Result();
		
		if (substr($this->webservice()->getResponseCode(), 0, 1) != 2)
		{
			$response = $this->webservice()->getResponseJsonObject();
			
			if (isset($response) && isset($response->errors))
			{
				foreach ($response->errors as $error) {
					$result->addError((string)$error->message, (string)$error->key);
				}
			}
		}
		
		return $result;
	}
}
