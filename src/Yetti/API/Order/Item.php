<?php

namespace Yetti\API;

/**
 * Order item model.
 * 
 * @author Sam Holman <sam@yetti.co.uk>
 * @copyright Copyright (c) 2011-2013, Yetti Ltd.
 * @package yetti-api
 */

class Order_Item extends BaseAbstract
{
	/**
	 * Returns the order resource ID
	 * 
	 * @return int
	 */
	public function getId()
	{
		return (int)$this->getJson()->id;
	}
	
	/**
	 * Returns the order item's resource ID
	 * 
	 * @return int
	 */
	public function getResourceId()
	{
		return (int)$this->getJson()->resource->resourceId;
	}
	
	/**
	* Returns the order item's name
	*
	* @return string
	*/
	public function getName()
	{
	    return (string)$this->getJson()->resource->name;
	}
	
	/**
	 * Returns the selected order item's combination ID
	 * 
	 * @return int
	 */
	public function getCombinationId()
	{
		return (int)$this->getJson()->combination->id;
	}
	
	/**
	 * Returns the order item's SKU
	 * 
	 * @return string
	 */
	public function getSku()
	{
		return (string)$this->getJson()->combination->sku;
	}
	
	/**
	* Returns the order item's combination options
	*
	* @return array
	*/
	public function getCombinationOptions()
	{
	    return $this->getJson()->combination->options;
	}
	
	/**
	 * Returns the quantity of the order item ordered
	 * 
	 * @return float
	 */
	public function getQuantity()
	{
		return (float)$this->getJson()->quantity;
	}
	
	/**
	* Returns the order item's price excluding VAT
	*
	* @return float
	*/
	public function getPrice()
	{
	    return (float)$this->getJson()->price;
	}
	
	/**
	* Returns the order item's price including VAT
	*
	* @return float
	*/
	public function getPriceIncVat()
	{
	    return (float)$this->getJson()->priceIncVat;
	}
	
	/**
	* Returns the VAT rate for this order item (if applicable)
	*
	* @return float
	*/
	public function getVatRate()
	{
	    return (float)$this->getJson()->vat;
	}
}