<?php

namespace Mpdf\QrCode\Output;

use Mpdf\QrCode\QrCode;

/**
 * @group unit
 */
class PngTest extends \PHPUnit\Framework\TestCase
{

	public function testOutput()
	{
		$code = new QrCode('LOREM IPSUM 2019');

		$output = new Png();

		$data = $output->output($code);

		$filename = __DIR__ . '/../../reference/LOREM-IPSUM-2019-L-100.png';
		$this->assertSame($data, file_get_contents($filename));

		$data = $output->output($code, 250);

		$filename = __DIR__ . '/../../reference/LOREM-IPSUM-2019-L-250.png';
		$this->assertSame($data, file_get_contents($filename));

		$code->disableBorder();

		$data = $output->output($code, 250);

		$filename = __DIR__ . '/../../reference/LOREM-IPSUM-2019-L-250-noborder.png';
		$this->assertSame($data, file_get_contents($filename));

		$code = new QrCode('LOREM IPSUM 2019', QrCode::ERROR_CORRECTION_QUARTILE);

		$data = $output->output($code);

		$filename = __DIR__ . '/../../reference/LOREM-IPSUM-2019-Q-100.png';
		$this->assertSame($data, file_get_contents($filename));

		$data = $output->output($code, 250);

		$filename = __DIR__ . '/../../reference/LOREM-IPSUM-2019-Q-250.png';
		$this->assertSame($data, file_get_contents($filename));
	}
}
