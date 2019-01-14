<?php

namespace Mpdf\QrCode;

/**
 * @group unit
 */
class QrCodeTest extends \PHPUnit\Framework\TestCase
{

	public function testQrCodeAlnum()
	{
		$qrCode = new QrCode('LOREM IPSUM 2019');

		$this->assertFalse($qrCode->isBorderDisabled());
		$this->assertSame(29, $qrCode->getQrSize());

		$qrCode->disableBorder();

		$this->assertTrue($qrCode->isBorderDisabled());
		$this->assertSame(21, $qrCode->getQrSize());
	}

	public function testQrCodeBin()
	{
		$qrCode = new QrCode('Lorem ipsum dolor sit amet');

		$this->assertFalse($qrCode->isBorderDisabled());
	}

	public function testQrCodeNumeric()
	{
		$qrCode = new QrCode('5548741164863348');

		$this->assertFalse($qrCode->isBorderDisabled());

		$this->assertCount(841, $qrCode->getFinal());
	}

	/**
	 * @expectedException  \Mpdf\QrCode\QrCodeException
	 */
	public function testInvalidErrorCorrection()
	{
		new QrCode('Invalid ECC', 'X');
	}

	/**
	 * @expectedException  \Mpdf\QrCode\QrCodeException
	 */
	public function testEmptyValue()
	{
		new QrCode('', 'L');
	}

}
