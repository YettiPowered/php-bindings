<?php

namespace Yetti\API;

/**
 * Abstract base class for Yetti API bindings
 *
 * @author Sam Holman <sam@yetti.co.uk>
 * @copyright Copyright (c) 2011-2012, Yetti Ltd.
 * @package yetti-api
 */

abstract class BaseAbstract
{
	private
	
		/**
		 * The webservice object
		 * 
		 * @var \Yetti\API\Webservice
		 */
		$_webservice,
		
		/**
		 * Holds the json object that represents the derived model's data
		 * 
		 * @var \stdClass
		 */
		$_json;
	
	/**
	 * Set the webservice object
	 *
	 * @param Webservice $webservice
	 * @return void
	 */
	public function setWebservice(Webservice $webservice)
	{
		$this->_webservice = $webservice;
	}
	
	/**
	 * Returns the webservice object
	 *
	 * @return Webservice
	 */
	public function webservice()
	{
		if (!$this->_webservice) {
			$this->_webservice = new \Yetti\API\Webservice();
		}
		
		return $this->_webservice;
	}
	
	/**
	 * Set the JSON object
	 *
	 * @param stdClass $json
	 * @return void
	 */
	public function setJson($json)
	{
		if (is_array($json)) {
			$json = $this->getArrayAsObject($json);
		}
		
		$this->_json = $json;
	}
	
	/**
	 * Returns the JSON object
	 * 
	 * @throws Exception
	 * @return \stdClass
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
	 * @return \Yetti\API\Result
	 */
	protected function makeRequestReturnResult()
	{
		$this->webservice()->makeRequest();
		$result = new \Yetti\API\Result();
		
		if (substr($this->webservice()->getResponseCode(), 0, 1) != 2)
		{
			$response = $this->webservice()->getResponseJsonObject();
			
			if (isset($response))
			{
				if (isset($response->errors))
				{
					foreach ($response->errors as $error) {
						$result->addError($error->message, $error->key);
					}
				}
				elseif (isset($response->message)) {
					$result->addError($response->message, 'error');
				}
			}
		}
		
		return $result;
	}
	
	/**
	 * Recursively convert the given array into a stdClass object
	 * 
	 * @param array $array
	 * @return stdClass
	 */
	private function getArrayAsObject($array)
	{
		if (is_array($array)) {
			return (object)array_map(array(__CLASS__, __FUNCTION__), $array);
		}
		
		return $array;
	}
}
