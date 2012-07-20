<?php

/**
 * Webservice class handles the actual cURL calls and responses to and from Yetti
 *
 * @author Sam Holman <sam@yetti.co.uk>
 * @copyright Copyright (c) 2011-2012, Yetti Ltd.
 */

class LabelEdAPI_WebService
{
	private
		$_baseUri,
		$_requestUri,
		$_accessKey,
		$_privateKey,
		$_version = '2',
		$_requestMethod = 'GET',
		$_requestParams = array(),
		$_requestSignature,
		$_requestHeaders = array(),
		$_postData,
		$_responseCode,
		$_responseHeaders,
		$_response,
		$_responseJsonObject,
		$_rawResponse;
	
	private static 
		$_defaultBaseUri,
		$_defaultAccessKey,
		$_defaultPrivateKey;
		
	public function __construct() {}
	
	/**
	 * Set a default base URI (used when no other base URI has been set)
	 * 
	 * @param string $uri
	 * @return void
	 */
	public static function setDefaultBaseUri($uri)
	{
		if (is_string($uri)) {
			self::$_defaultBaseUri = $uri;
		}
	}
	
	/**
	 * Set a default access key (used when no other key has been set)
	 * 
	 * @param string $accessKey
	 * @return void
	 */
	public static function setDefaultAccessKey($accessKey)
	{
		if (is_string($accessKey)) {
			self::$_defaultAccessKey = $accessKey;
		}
	}
	
	/**
	 * Set a default private key (used when no other key has been set)
	 * 
	 * @param string $privateKey
	 * @return void
	 */
	public static function setDefaultPrivateKey($privateKey)
	{
		if (is_string($privateKey)) {
			self::$_defaultPrivateKey = $privateKey;
		}
	}
	
	/**
	 * Set the base URI for all requests
	 *
	 * @param string $uri
	 * @return void
	 */
	public function setBaseUri($uri)
	{
		if (is_string($uri)) {
			$this->_baseUri = $uri;
		}
	}
	
	/**
	 * Returns the base URI
	 * 
	 * @return string
	 */
	public function getBaseUri()
	{
		return $this->_baseUri ? $this->_baseUri : self::$_defaultBaseUri;
	}
	
	/**
	 * Set the URI path for the specific webservice request
	 *
	 * @param string $path
	 * @return void
	 */
	public function setRequestPath($path)
	{
		if (is_string($path))
		{
			if (substr($path, -1) != '?') {
				$path .= '?';
			}
			
			$this->_requestUri = $this->getBaseUri() . '/' . $this->_version . $path;
		}
	}
	
	/**
	 * Set your webservice access key
	 *
	 * @param string $accessKey
	 * @return void
	 */
	public function setAccessKey($accessKey)
	{
		if (is_string($accessKey)) {
			$this->_accessKey = $accessKey;
		}
	}
	
	/**
	 * Returns the access key
	 * 
	 * @return string
	 */
	public function getAccessKey()
	{
		return $this->_accessKey ? $this->_accessKey : self::$_defaultAccessKey;
	}
	
	/**
	 * Set your webservice private key for authentication
	 *
	 * @param string $privateKey
	 * @return void
	 */
	public function setPrivateKey($privateKey)
	{
		if (is_string($privateKey)) {
			$this->_privateKey = $privateKey;
		}
	}
	
	/**
	 * Returns the private key
	 * 
	 * @return string
	 */
	public function getPrivateKey()
	{
		return $this->_privateKey ? $this->_privateKey : self::$_defaultPrivateKey;
	}
	
	/**
	 * Set your webservice API version
	 *
	 * @param string $version
	 * @return void
	 */
	public function setVersion($version)
	{
		$this->_version = (string)$version;
	}
	
	/**
	 * Set the type of request (post or get)
	 *
	 * @param string $method
	 * @return void
	 */
	public function setRequestMethod($method)
	{
		$method = strtoupper($method);
		
		if ($method == 'POST' || $method == 'PUT' || $method == 'DELETE' || $method == 'GET' || $method == 'HEAD') {
			$this->_requestMethod = $method;
		}
	}
	
	/**
	 * Returns the request method
	 * 
	 * @return string
	 */
	public function getRequestMethod()
	{
		return $this->_requestMethod;
	}
	
	/**
	 * Returns the full request URI
	 * 
	 * @return string
	 */
	public function getRequestUri()
	{
		/**
		 * Make sure we don't have any existing auth params
		 */
		unset($this->_requestParams['accessKey']);
		unset($this->_requestParams['signature']);
		
		$this->setRequestParam('timestamp', time());
		
		$queryString = '';
		
		foreach ($this->_requestParams as $var => $val) {
			$queryString .= $var . '=' . $val . '&';
		}
		
		$queryString = substr($queryString, 0, -1);
		return $this->_requestUri . $queryString;
	}
	
	/**
	 * Set a parameter to be sent with your request
	 *
	 * @param string $name
	 * @param string $value
	 * @return bool
	 */
	public function setRequestParam($name, $value)
	{
		if (is_string($name))
		{
			$this->_requestParams[$name] = urlencode(utf8_encode((string)$value));
			return true;
		}
		
		return false;
	}
	
	/**
	 * Set raw post data to be sent
	 *
	 * @param string $data
	 * @return bool
	 */
	public function setPostData($data)
	{
		if (is_string($data))
		{
			$this->_postData = $data;
			return true;
		}
		
		return false;
	}
	
	/**
	 * Add a request header
	 * 
	 * @param string $header
	 * @return void
	 */
	public function addRequestHeader($header)
	{
		if (is_string($header)) {
			$this->_requestHeaders[] = $header;
		}
	}
	
	/**
	 * Resets any previously set request parameters and response
	 * Call this method to reset your webservice object so that
	 * you can safely make another request.
	 *
	 * @return void
	 */
	public function resetRequest()
	{
		$this->_requestParams = array();
		$this->_postData = '';
		$this->_response = false;
	}
	
	/**
	 * Make the webservice request
	 *
	 * @return bool
	 * @throws Exception if required values not yet set
	 */
	public function makeRequest()
	{
		if (!$this->_requestUri || substr($this->_requestUri, 0, 4) != 'http') {
			throw new Exception('Request URI not set');
		}
		
		$requestUri = $this->getRequestUri();
		$this->_requestSignature = hash_hmac('sha256', $requestUri, $this->getPrivateKey());
		
		if (!$request = curl_init($requestUri)) {
			throw new Exception('Failed to initialise curl');
		}

		switch ($this->_requestMethod)
		{
			case 'HEAD':
				curl_setopt($request, CURLOPT_NOBODY, true);
				break;

			case 'POST':
				curl_setopt($request, CURLOPT_POST, true);
				break;
		}
		
		if (!empty($this->_postData)) {
			curl_setopt($request, CURLOPT_POSTFIELDS, $this->_postData);
		}
		
		curl_setopt($request, CURLOPT_CUSTOMREQUEST, $this->_requestMethod);
		curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($request, CURLOPT_HEADER, true);
		curl_setopt($request, CURLOPT_HTTPHEADER, array_merge(array(
			'X-Authorization: ' . $this->getAccessKey() . ':' . $this->getRequestSignature(),
		), $this->_requestHeaders));
		curl_setopt($request, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($request, CURLOPT_USERAGENT, 'LabelEdWebServicePHP');
		
		$this->parseResponse(curl_exec($request));
		curl_close($request);
		
		if ($this->_responseCode == 503 && isset($this->_responseHeaders['Retry-After']))
		{
			if ($this->_responseHeaders['Retry-After'] <= 60)
			{
				sleep($this->_responseHeaders['Retry-After']);
				return $this->makeRequest();
			}
			else {
				throw new Exception('Webservice failed with 503 header and requested retry was unreasonable to wait.');
			}
		}
		
		if ($this->_response && is_string($this->_response) && $json = json_decode($this->_response)) {
			$this->_responseJsonObject = $json;
		}
		
		if ($this->_responseCode == 401 || $this->_responseCode == 403) {
			throw new Exception('Webservice authentication failed');
		}
		
		return $this->_responseCode == 200;
	}
	
	/**
	 * Parses the response into headers and body
	 *
	 * @param string $response
	 * @return void
	 */
	private function parseResponse($response)
	{
		$headerCount = 2;
		
		if (substr($response, 0, 21) == 'HTTP/1.1 100 Continue') {
			$headerCount = 3;
		}
		$headers		= explode("\r\n\r\n", $response, $headerCount);
		$responseCode	= false;
		$responseBody	= array_pop($headers);
		$headers = explode("\r\n", array_pop($headers));
		
		$headerArray 	= array();
		
		foreach ($headers as $line)
		{
			if (preg_match('@^HTTP/[0-9]\.[0-9] ([0-9]{3})@', $line, $matches)) {
				$responseCode = $matches[1];
			}
			else
			{
				list($header, $value) = explode(': ', $line, 2);
				$headerArray[$header] = $value;
			}
		}
		
		$this->_rawResponse	 	= $response;
		$this->_responseCode 	= $responseCode;
		$this->_responseHeaders = $headerArray;
		$this->_response 		= trim($responseBody);
	}
	
	/**
	 * Returns the last used request signature
	 * 
	 * @return string
	 */
	public function getRequestSignature()
	{
		return $this->_requestSignature;
	}
	
	/**
	 * Returns whether or not the request was successful
	 *
	 * @return bool
	 */
	public function success()
	{
		return $this->getResponseStatus() == 'success';
	}
	
	/**
	 * Returns the HTTP response status code
	 *
	 * @return int
	 */
	public function getResponseCode()
	{
		return $this->_responseCode;
	}
	
	/**
	 * Returns the full (raw) response string
	 * 
	 * @return string
	 */
	public function getRawResponse()
	{
		return $this->_rawResponse;
	}
	
	/**
	 * Returns the response
	 *
	 * @return string
	 */
	public function getResponse()
	{
		return $this->_response;
	}
	
	/**
	 * Returns a JSON object from response
	 *
	 * @return stdClass
	 */
	public function getResponseJsonObject()
	{
		return $this->_responseJsonObject;
	}
	
	/**
	 * Returns the status of the response (usually success or failed)
	 *
	 * @return string
	 * @throws Exception if no valid response
	 */
	public function getResponseStatus()
	{
		if (!is_object($this->_response)) {
			throw new Exception('No valid response received or request not yet sent');
		}
		
		return $this->_response->attributes()->status;
	}
	
	/**
	 * Returns the response message
	 *
	 * @return string
	 */
	public function getResponseMessage()
	{
		return $this->parseMessageFromResponse($this->_response);
	}
	
	/**
	 * Gets the value of a response header
	 * 
	 * @param string $name
	 * @return string
	 */
	public function getResponseHeader($name)
	{
		return isset($this->_responseHeaders[$name]) ? $this->_responseHeaders[$name] : null;
	}
	
	/**
	 * Parses a message from the LabelEd webservice response JSON
	 *
	 * @param $json
	 * @return string
	 */
	private function parseMessageFromResponse($json)
	{
		$message = 'No message found';
		
		if (is_object($json))
		{			
			if (isset($json->message)) {
				$message = $json->message;
			}
		}
		
		return $message;
	}
	
	public function __destruct() {}
}
