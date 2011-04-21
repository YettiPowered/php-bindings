<?php

class LabelEdAPIResult
{
	private
		$_errors = array();
	
	/**
	 * Add an error to this result
	 *
	 * @param string $error
	 * @return void
	 */
	public function addError($error)
	{
		$this->_errors[] = $error;
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