<?php

namespace Yetti\API;

/**
 * VAT band model.
 * 
 * @author Sam Holman <sam@yetti.co.uk>
 * @copyright Copyright (c) 2011-2012, Yetti Ltd.
 * @package yetti-api
 */

class VAT extends BaseAbstract
{
	/**
	 * Load a VAT band by ID
	 * 
	 * @param int $bandId
	 * @return bool
	 */
	public function load($bandId)
	{
		$this->webservice()->setRequestPath('/vat/' . $bandId . '.ws');
		$this->webservice()->setRequestMethod('get');
		
		if ($this->webservice()->makeRequest())
		{
			$this->setJson($this->webservice()->getResponseJsonObject()->band);
			return true;
		}
		
		return false;
	}
	
	/**
	 * Load a VAT band template
	 * 
	 * @return bool
	 */
	public function loadTemplate()
	{
		$this->webservice()->setRequestPath('/templates/vat.ws');
		$this->webservice()->setRequestMethod('get');
		
		if ($this->webservice()->makeRequest())
		{
			if ($json = $this->webservice()->getResponseJsonObject())
			{
				$this->setJson($json->band);
				return true;
			}
		}
		
		return false;
	}
	
	/**
	 * Save this VAT band
	 * 
	 * @return \Yetti\API\Result
	 */
	public function save()
	{
		$this->webservice()->setRequestPath('/vat.ws');
		$this->webservice()->setRequestMethod('post');
		
		if ($this->getId())
		{
			$this->webservice()->setRequestMethod('put');
			$this->webservice()->setRequestParam('bandId', $this->getId());
		}
		
		$this->webservice()->setPostData(json_encode(array('band' => $this->getJson())));
		$result = $this->makeRequestReturnResult();
		
		if ($result->success()) {
			$this->getJson()->id = (int)$this->webservice()->getResponseHeader('X-ResourceId');
		}
		
		return $result;
	}
	
	/**
	 * Returns the band ID
	 * 
	 * @return int
	 */
	public function getId()
	{
		return (int)$this->getJson()->id;
	}
}
