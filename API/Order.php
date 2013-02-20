<?php

namespace Yetti\API;

/**
 * Order model.
 * 
 * @author Sam Holman <sam@yetti.co.uk>
 * @copyright Copyright (c) 2011-2012, Yetti Ltd.
 * @package yetti-api
 */

class Order extends BaseAbstract
{
	/**
	 * Load an order template
	 * 
	 * @return bool
	 */
	public function loadTemplate()
	{
		$this->webservice()->setRequestPath('/templates/order.ws');
		$this->webservice()->setRequestMethod('get');
		
		if ($this->webservice()->makeRequest())
		{
			if ($json = $this->webservice()->getResponseJsonObject())
			{
				$this->setJson($json->order);
				return true;
			}
		}
		
		return false;
	}
	
	/**
	 * Save this order
	 * 
	 * @return \Yetti\API\Result
	 */
	public function save()
	{
		$this->webservice()->setRequestPath('/orders.ws');
		$this->webservice()->setRequestMethod('post');
		
		if ($this->getId())
		{
			$this->webservice()->setRequestMethod('patch');
			$this->webservice()->setRequestParam('orderId', $this->getId());
		}
		
		$this->webservice()->setPostData(json_encode(array('order' => $this->getJson())));
		$result = $this->makeRequestReturnResult();
		
		if ($result->success() && !$this->getId()) {
			$this->getJson()->id = (int)$this->webservice()->getResponseHeader('X-ResourceId');
		}
		
		return $result;
	}
	
	/**
	 * Returns the order ID
	 * 
	 * @return int
	 */
	public function getId()
	{
		return (int)$this->getJson()->id;
	}
	
	/**
	 * Set the status ID
	 * 
	 * @param int $statusId
	 * @return void
	 */
	public function setStatusId($statusId)
	{
		$this->getJson()->statusId = (int)$statusId;
	}
	
	/**
	 * Returns the order's status ID
	 * 
	 * @return int
	 */
	public function getStatusId()
	{
		return (int)$this->getJson()->statusId;
	}
	
	/**
	 * Set the ID of the user who "owns" this order
	 * 
	 * @param int $userId
	 * @return void
	 */
	public function setUserId($userId)
	{
		$this->getJson()->user->id = (int)$userId;
	}
	
	/**
	 * Returns the ID of the user who "owns" this order
	 * 
	 * @return int
	 */
	public function getUserId()
	{
		return (int)$this->getJson()->user->id;
	}
}
