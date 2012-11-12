<?php

namespace Yetti\API\Tests;
use Yetti\API\VAT;

/**
 * Test methods for the VAT model.
 *
 * @author Sam Holman <sam@yetti.co.uk>
 * @copyright Copyright (c) 2011-2012, Yetti Ltd.
 * @package yetti-api
 * @subpackage tests
 */

class VatTest extends AuthAbstract
{
	public function testNewVatBand()
	{
		$vatBand = new VAT();
		$vatBand->loadTemplate();
		$this->assertFalse($vatBand->save()->success());
		
		$vatBand->setName('VAT band');
		$vatBand->setPercentage(20);
		$this->assertTrue($vatBand->save()->success());
		
		$this->assertFalse($vatBand->isDefault());
		$vatBand->setDefault();
		$this->assertTrue($vatBand->isDefault());
		$this->assertTrue($vatBand->save()->success());
		
		return $vatBand;
	}
	
	/**
	 * @depends testNewVatBand
	 */
	public function testLoad(VAT $inVatBand)
	{
		$vatBand = new VAT();
		$this->assertTrue($vatBand->load($inVatBand->getId()));
		
		$this->assertEquals('VAT band', $vatBand->getName());
		$this->assertEquals(20, $vatBand->getPercentage());
	}
}
