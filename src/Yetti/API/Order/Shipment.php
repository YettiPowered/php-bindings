<?php

namespace Yetti\API;

/**
 * Order shipment model.
 * 
 * @author Sam Holman <sam@yetti.co.uk>
 * @copyright Copyright (c) 2013, Yetti Ltd.
 * @package yetti-api
 */

class Order_Shipment extends BaseAbstract
{
	private
	
		/**
		 * The ID of the order to which this shipment relates
		 * 
		 * @var int
		 */
		$_orderId;
	
	/**
	 * Construct a new order shipment object
	 * 
	 * @return void
	 */
	public function __construct()
	{
		$this->setJson(array(
			'id'		=> null,
			'tracking'	=> null,
			'packageId' => null,
			'courierId' => null,
			'items' => array(),
		));
	}
	
	/**
	 * Load an order shipment by ID
	 * 
	 * @param int $orderId
	 * @param int $shipmentId
	 * @return bool
	 */
	public function load($orderId, $shipmentId)
	{
		$this->webservice()->setRequestPath('/orders/' . $orderId . '/shipments/' . $shipmentId . '.ws');
		$this->webservice()->setRequestMethod('get');
		
		if ($this->webservice()->makeRequest())
		{
			if ($shipment = isset($this->webservice()->getResponseJsonObject()->shipment) ? $this->webservice()->getResponseJsonObject()->shipment : null)
			{
				$this->_orderId = $orderId;
				$this->setJson($shipment);
				return true;
			}
		}
		
		return false;
	}
	
	/**
	 * Save this order shipment
	 * 
	 * @return \Yetti\API\Result
	 */
	public function save()
	{
		$this->webservice()->setRequestPath('/orders/' . $this->getOrderId() . '/shipments.ws');
		$this->webservice()->setRequestMethod('post');
		
		if ($shipmentId = $this->getId())
		{
			$this->webservice()->setRequestMethod('put');
			$this->webservice()->setRequestParam('shipmentId', $shipmentId);
		}
		
		$this->webservice()->setPostData(json_encode(array('shipment' => $this->getJson())));
		$result = $this->makeRequestReturnResult();
		
		if ($result->success() && !$this->getId()) {
			$this->getJson()->id = (int)$this->webservice()->getResponseHeader('X-ResourceId');
		}
		
		return $result;
	}
	
	/**
	 * Returns the shipment ID
	 * 
	 * @return int
	 */
	public function getId()
	{
		return (int)$this->getJson()->id;
	}
	
	/**
	 * Set the ID of the order to which this shipment relates
	 * 
	 * @param int $id
	 * @return void
	 */
	public function setOrderId($id)
	{
		$this->_orderId = (int)$id;
	}
	
	/**
	 * Returns the order ID for this shipment
	 * 
	 * @return int
	 */
	public function getOrderId()
	{
		return $this->_orderId;
	}
	
	/**
	 * Set the courier tracking code for this shipment
	 * 
	 * @param string $tracking
	 * @return void
	 */
	public function setTracking($tracking)
	{
		$this->getJson()->tracking = (string)$tracking;
	}
	
	/**
	 * Returns the courier tracking code for this shipment
	 * 
	 * @return string
	 */
	public function getTracking()
	{
		return (string)$this->getJson()->tracking;
	}
	
	/**
	 * Set the package ID for this shipment
	 * 
	 * @param int $id
	 * @return void
	 */
	public function setPackageId($id)
	{
		$this->getJson()->packageId = (int)$id;
	}
	
	/**
	 * Returns the package ID
	 * 
	 * @return int
	 */
	public function getPackageId()
	{
		return (int)$this->getJson()->packageId;
	}
	
	/**
	 * Set the courier ID for this shipment
	 * 
	 * @param int $id
	 * @return void
	 */
	public function setCourierId($id)
	{
		$this->getJson()->courierId = (int)$id;
	}
	
	/**
	 * Returns the courier ID
	 * 
	 * @return int
	 */
	public function getCourierId()
	{
		return (int)$this->getJson()->courierId;
	}
	
	/**
	 * Add an item to this shipment (by order line ID)
	 * 
	 * @param int $id
	 * @return void
	 */
	public function addItem($id)
	{
		$this->getJson()->items = (array)$this->getJson()->items;
		
		$this->getJson()->items[] = array(
			'orderLineId' => (int)$id,
		);
	}
}
