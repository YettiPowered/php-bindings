<?php

/**
 * Abstract base class for LabelEd APIs
 *
 * $Id$
 *
 */
abstract class LabelEdAPI_Abstract
{
	private $_webservice;
	
	public final function __construct(LabelEdWebService $webservice)
	{
		$webservice->resetRequest();
		$this->_webservice = $webservice;
	}
	
	/**
	 * Returns a webservice object
	 *
	 * @return LabelEdWebService
	 */
	public function webservice()
	{
		return $this->_webservice;
	}
	
	public function __destruct() {}
}
