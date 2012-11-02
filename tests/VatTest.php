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
	public function testSaveVatBand()
	{
		$vatBand = new VAT();
		$vatBand->loadTemplate();
		$this->assertFalse($vatBand->save()->success());
		
		
	}
}
