<?php

/**
 * Webservice class handles the actual CURL calls and responses to LabelEd
 *
 * $Id$
 *
 */
class LabelEdAPI_WebService
{
	private
		$_baseUri,
		$_requestUri,
		$_accessKey,
		$_privateKey,
		$_version='1.0',
		$_requestMethod,
		$_requestParams = array(),
		$_postData,
		$_responseCode,
		$_responseHeaders,
		$_response,
		$_responseXmlObject;
	
	public function __construct()
	{
		include('config.inc.php');
	}
	
	/**
	 * Set the base URI for all requests
	 *
	 * @param string $uri
	 * @return bool
	 */
	public function setBaseUri($uri)
	{
		if (is_string($uri))
		{
			$this->_baseUri = $uri;
			return true;
		}
		
		return false;
	}
	
	/**
	 * Set the URI path for the specific webservice request
	 *
	 * @param string $path
	 * @return bool
	 */
	public function setRequestPath($path)
	{
		if (is_string($path))
		{
			if (substr($path, -1) != '?') {
				$path .= '?';
			}
			
			$this->_requestUri = $this->_baseUri . '/' . $this->_version . $path;
			return true;
		}
		
		return false;
	}
	
	/**
	 * Set your webservice access key
	 *
	 * @param string $accessKey
	 * @return bool
	 */
	public function setAccessKey($accessKey)
	{
		if (is_string($accessKey))
		{
			$this->_accessKey = $accessKey;
			return true;
		}
		
		return false;
	}
	
	/**
	 * Set your webservice private key for authentication
	 *
	 * @param string $privateKey
	 * @return bool
	 */
	public function setPrivateKey($privateKey)
	{
		if (is_string($privateKey))
		{
			$this->_privateKey = $privateKey;
			return true;
		}
		
		return false;
	}
	
	/**
	 * Set your webservice API version
	 *
	 * @param string $version
	 * @return bool
	 */
	public function setVersion($version)
	{
		$this->_version = (string)$version;
		return true;
	}
	
	/**
	 * Set the type of request (post or get)
	 *
	 * @param string $method
	 * @return bool
	 */
	public function setRequestMethod($method)
	{
		$method = strtoupper($method);
		
		if ($method == 'POST' || $method == 'PUT' || $method == 'DELETE' || $method == 'GET' || $method == 'HEAD')
		{
			$this->_requestMethod = $method;
			return true;
		}
		
		return false;
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
		
		$queryString 	= substr($queryString, 0, -1);
		$requestUri  	= $this->_requestUri . $queryString;
		$signature	 	= hash_hmac('sha256', $requestUri, $this->_privateKey);

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
		curl_setopt($request, CURLOPT_HTTPHEADER, array(
			'X-Authorization: ' . $this->_accessKey . ':' . $signature,
		));
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
		echo $this->_response;
		if ($this->_response && is_string($this->_response) && substr($this->_response, 0, 5) == '<?xml') {
			$this->_responseXmlObject = simplexml_load_string($this->_response);
		}
		
		if ($this->_responseCode == 403) {
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
		
		$this->_responseCode 	= $responseCode;
		$this->_responseHeaders = $headerArray;
		$this->_response 		= trim($responseBody);
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
	 * Returns the response
	 *
	 * @return string
	 */
	public function getResponse()
	{
		return $this->_response;
	}
	
	/**
	 * Returns a PHP xml object from response
	 *
	 * @return SimpleXMLElement
	 */
	public function getResponseXmlObject()
	{
		return $this->_responseXmlObject;
	}
	
	/**
	 * Returns the status of the response (usually success or failed)
	 *
	 * @return string
	 * @throws Exception if no valid response
	 */
	public function getResponseStatus()
	{
		if (!is_object($this->_response) || get_class($this->_response) != 'SimpleXMLElement') {
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
	 * Parses a message from the LabelEd webservice response xml
	 *
	 * @param $xml
	 * @return string
	 */
	private function parseMessageFromResponse($xml)
	{
		$message = 'No message found';
		
		if (is_object($xml) && get_class($xml) == 'SimpleXMLElement')
		{
			$child = $xml->children();
			
			if (isset($child->message)) {
				$message = $child->message;
			}
		}
		
		return $message;
	}
	
	public function __destruct() {}
}
