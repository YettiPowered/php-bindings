<?php

namespace Yetti\API\Tests;

/**
 * Test methods for the item filter type model.
 *
 * @author Sam Holman <sam@yetti.co.uk>
 * @copyright Copyright (c) 2011-2012, Yetti Ltd.
 * @package yetti-api
 * @subpackage tests
 */

class Item_Filter_TypeTest extends AuthAbstract
{
	public function testCreateFilterType()
	{
		$type = new \Yetti\API\Item_Filter_Type();
		$this->assertFalse($type->save()->success());
		
		$type->setItemTypeId(4);
		$this->assertFalse($type->save()->success());
		
		$type->setName('Colour');
		$this->assertTrue($type->save()->success());
		
		return $type;
	}
	
	/**
	 * @depends testCreateFilterType
	 */
	public function testLoad(\Yetti\API\Item_Filter_Type $inType)
	{
		$type = new \Yetti\API\Item_Filter_Type();
		$this->assertFalse($type->load(-1));
		$this->assertTrue($type->load($inType->getId()));
		$this->assertEquals('Colour', $type->getName());
		
		return $type;
	}
	
	/**
	 * @depends testLoad
	 */
	public function testUpdate(\Yetti\API\Item_Filter_Type $inType)
	{
		$typeId = $inType->getId();
		
		$inType->setName('Changed');
		$this->assertTrue($inType->save()->success());
		
		$type = new \Yetti\API\Item_Filter_Type();
		$this->assertTrue($type->load($typeId));
		$this->assertEquals('Changed', $type->getName());
	}
}
