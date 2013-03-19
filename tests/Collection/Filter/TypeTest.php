<?php

namespace Yetti\API\Tests;

/**
 * Test methods for the collection filter type model.
 *
 * @author Sam Holman <sam@yetti.co.uk>
 * @copyright Copyright (c) 2011-2013, Yetti Ltd.
 * @package yetti-api
 * @subpackage tests
 */

class Collection_Filter_TypeTest extends AuthAbstract
{
	public function testCreateFilterType()
	{
		$collection = new \Yetti\API\Collection();
		$collection->loadTemplate(4);
		$collection->setIdentifier('A collection with filters ' . microtime(true));
		$collection->setPropertyValue('Name', 'A collection with filters');
		$this->assertTrue($collection->save()->success());
		
		$type = new \Yetti\API\Collection_Filter_Type();
		$this->assertFalse($type->save()->success());
		
		$type->setCollectionId($collection->getId());
		$this->assertFalse($type->save()->success());
		
		$type->setName('Colour');
		$this->assertTrue($type->save()->success());
		
		return $type;
	}
	
	/**
	 * @depends testCreateFilterType
	 */
	public function testLoad(\Yetti\API\Collection_Filter_Type $inType)
	{
		$type = new \Yetti\API\Collection_Filter_Type();
		$this->assertFalse($type->load(-1));
		$this->assertTrue($type->load($inType->getId()));
		$this->assertEquals('Colour', $type->getName());
		
		return $type;
	}
	
	/**
	 * @depends testLoad
	 */
	public function testUpdate(\Yetti\API\Collection_Filter_Type $inType)
	{
		$typeId = $inType->getId();
		
		$inType->setName('Changed');
		$this->assertTrue($inType->save()->success());
		
		$type = new \Yetti\API\Collection_Filter_Type();
		$this->assertTrue($type->load($typeId));
		$this->assertEquals('Changed', $type->getName());
	}
}
