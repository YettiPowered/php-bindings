<?php

if ($this instanceof LabelEdAPI_WebService)
{
	/**
	 * The base url for the services
	 */
	$this->setBaseUri('http://whitelounge.tom.dev');
	
	/**
	 * The webservice API version number 1.0 at time of writing
	 */
	$this->setVersion('1.0');
	
	/**
	 * Accesskey is the username of the user we would like to our requests processed as 
	 */
	$this->setAccessKey('trawcliffe');
	
	/**
	 * Private key as provided by your website provider
	 */
	$this->setPrivateKey('jasndofiunbpweroinfpoasidnf');
}