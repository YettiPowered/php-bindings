<?php

namespace Yetti\API;

/**
 * Basic result object
 * 
 * @author Sam Holman <sam@yetti.co.uk>
 * @copyright Copyright (c) 2011-2012, Yetti Ltd.
 * @package yetti-api
 */

class Result
{
	private
	
		/**
		 * Holds error strings
		 * 
		 * @var array
		 */
		$_errors = array();
	
	/**
	 * Add an error to this result
	 *
	 * @param string $error
	 * @param string $key
	 * @return void
	 */
	public function addError($error, $key)
	{
		$this->_errors[$key] = $error;
	}
	
	/**
	 * Return an array of errors
	 *
	 * @return array
	 */
	public function getErrors()
	{
		return $this->_errors;
	}
	
	/**
	 * Return whether or not this result was a success (no errors)
	 *
	 * @return bool
	 */
	public function success()
	{
		return !count($this->_errors);
	}
}
