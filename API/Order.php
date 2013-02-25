<?php

namespace Yetti\API;
use Yetti\API\User_Address;

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
	 * Load an order by ID
	 * 
	 * @param int $orderId
	 * @return bool
	 */
	public function load($orderId)
	{
		$this->webservice()->setRequestPath('/orders/' . $orderId . '.ws');
		$this->webservice()->setRequestMethod('get');
		
		if ($this->webservice()->makeRequest())
		{
			if ($order = isset($this->webservice()->getResponseJsonObject()->order) ? $this->webservice()->getResponseJsonObject()->order : null)
			{
				$this->setJson($order);
				return true;
			}
		}
		
		return false;
	}
	
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
	
	/**
	 * Sets the shipping address ID
	 * 
	 * @param int $id
	 * @return void
	 */
	public function setShippingAddressId($id)
	{
		$this->getJson()->user->addresses->shipping->id = (int)$id;
	}
	
	/**
	 * Returns the shipping address ID
	 * 
	 * @return int
	 */
	public function getShippingAddressId()
	{
		return (int)$this->getJson()->user->addresses->shipping->id;
	}
	
	/**
	 * Returns an address object representing shipping details for this order
	 * 
	 * @return User_Address
	 */
	public function getShippingAddress()
	{
		$address = new User_Address();
		$address->load($this->getUserId(), $this->getShippingAddressId());
		
		return $address;
	}
	
	/**
	 * Sets the billing address ID
	 * 
	 * @param int $id
	 * @return void
	 */
	public function setBillingAddressId($id)
	{
		$this->getJson()->user->addresses->billing->id = (int)$id;
	}
	
	/**
	 * Returns the billing address ID
	 * 
	 * @return int
	 */
	public function getBillingAddressId()
	{
		return (int)$this->getJson()->user->addresses->billing->id;
	}
	
	/**
	 * Returns an address object representing billing details for this order
	 * 
	 * @return User_Address
	 */
	public function getBillingAddress()
	{
		$address = new User_Address();
		$address->load($this->getUserId(), $this->getBillingAddressId());
		
		return $address;
	}
	
	/**
	 * Set the courier ID
	 * 
	 * @param int $id
	 * @return void
	 */
	public function setCourierId($id)
	{
		$this->getJson()->courier->id = (int)$id;
	}
	
	/**
	 * Returns the courier ID
	 * 
	 * @return int
	 */
	public function getCourierId()
	{
		return (int)$this->getJson()->courier->id;
	}
	
	/**
	 * Add an item to this order
	 * 
	 * @param int $itemId
	 * @param int $combinationId
	 * @param float $quantity
	 * @return void
	 */
	public function addItem($itemId, $combinationId, $quantity)
	{
		$this->getJson()->items[] = array(
			'resource' => array(
				'resourceId' => (int)$itemId,
			),
			'combination' => array(
				'id' => (int)$combinationId,
			),
			'quantity' => (float)$quantity,
		);
	}
	
	/**
	 * Returns an array of items in this order
	 * 
	 * @return array
	 */
	public function getItems()
	{
		$items = array();
		
		foreach ($this->getJson()->items as $itemData)
		{
			$items[] = array(
				'resource' => array(
					'resourceId' => (int)$itemData->resource->resourceId,
				),
				'combination' => array(
					'id' => (int)$itemData->combination->id,
				),
				'quantity' => (float)$itemData->quantity,
			);
		}
		
		return $items;
	}
}
