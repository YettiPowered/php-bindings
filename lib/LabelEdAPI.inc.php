<?php

include_once('LabelEdWebService.inc.php');
include_once('APIs/Abstract.inc.php');
include_once('Result.inc.php');

/**
 * LabelEd API class, provides easy interfaces for dealing with LabelEd data
 *
 * $Id$
 *
 */
class LabelEdAPI
{
	private
		$_webservice,
		
		$_apiClasses = array(
			'items' => 'LabelEdAPI_Items',
		);
	
	public function __construct($baseUri, $accessKey, $privateKey)
	{
		$this->_webservice = new LabelEdWebService();
		$this->_webservice->setBaseUri($baseUri);
		$this->_webservice->setAccessKey($accessKey);
		$this->_webservice->setPrivateKey($privateKey);
	}
	
	public function __get($var)
	{
		if (!empty($this->_apiClasses[$var]))
		{
			$className = $this->_apiClasses[$var];
			list($prefix, $filename) = explode('_', $className);
			
			include_once('APIs/' . $filename . '.inc.php');
			return new $className($this->_webservice);
		}
	}
	
	public function __destruct() {}
}
