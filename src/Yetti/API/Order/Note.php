<?php

namespace Yetti\API;

/**
 * Order note model.
 * 
 * @author Sam Holman <sam@yetti.co.uk>
 * @copyright Copyright (c) 2013, Yetti Ltd.
 * @package yetti-api
 */

class Order_Note extends BaseAbstract
{
	private
	
		/**
		 * The ID of the order to which this note is attached
		 * 
		 * @var int
		 */
		$_orderId;
	
	/**
	 * Construct a new order note object
	 * 
	 * @return void
	 */
	public function __construct()
	{
		$this->setJson(array(
			'id'   => null,
			'time' => null,
			'note' => null,
		));
	}
	
	/**
	 * Load an order note by ID
	 * 
	 * @param int $orderId
	 * @param int $noteId
	 * @return bool
	 */
	public function load($orderId, $noteId)
	{
		$this->webservice()->setRequestPath('/orders/' . $orderId . '/notes/' . $noteId . '.ws');
		$this->webservice()->setRequestMethod('get');
		
		if ($this->webservice()->makeRequest())
		{
			if ($note = isset($this->webservice()->getResponseJsonObject()->note) ? $this->webservice()->getResponseJsonObject()->note : null)
			{
				$this->_orderId = $orderId;
				$this->setJson($note);
				return true;
			}
		}
		
		return false;
	}
	
	/**
	 * Save this order note
	 * 
	 * @return \Yetti\API\Result
	 */
	public function save()
	{
		$this->webservice()->setRequestPath('/orders/' . $this->getOrderId() . '/notes.ws');
		$this->webservice()->setRequestMethod('post');
		
		if ($noteId = $this->getId())
		{
			$this->webservice()->setRequestMethod('put');
			$this->webservice()->setRequestParam('noteId', $noteId);
		}
		
		$this->webservice()->setPostData(json_encode(array('note' => $this->getJson())));
		$result = $this->makeRequestReturnResult();
		
		if ($result->success() && !$this->getId()) {
			$this->getJson()->id = (int)$this->webservice()->getResponseHeader('X-ResourceId');
		}
		
		return $result;
	}
	
	/**
	 * Returns the note ID
	 * 
	 * @return int
	 */
	public function getId()
	{
		return (int)$this->getJson()->id;
	}
	
	/**
	 * Set the ID of the order to which this note is attached
	 * 
	 * @param int $id
	 * @return void
	 */
	public function setOrderId($id)
	{
		$this->_orderId = (int)$id;
	}
	
	/**
	 * Returns the order ID for this note
	 * 
	 * @return int
	 */
	public function getOrderId()
	{
		return $this->_orderId;
	}
	
	/**
	 * Set the note content
	 * 
	 * @param string $note
	 * @return void
	 */
	public function setNote($note)
	{
		if (is_string($note)) {
			$this->getJson()->note = $note;
		}
	}
	
	/**
	 * Returns the note content
	 * 
	 * @return string
	 */
	public function getNote()
	{
		return (string)$this->getJson()->note;
	}
}
