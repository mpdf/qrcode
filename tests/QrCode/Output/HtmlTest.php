<?php

namespace Mpdf\QrCode\Output;

use Mpdf\QrCode\QrCode;

/**
 * @group unit
 */
class HtmlTest extends \PHPUnit\Framework\TestCase
{

	public function testOutput()
	{
		$code = new QrCode('LOREM IPSUM 2019');

		$output = new Html();

		$data = $output->output($code);

		$filename = __DIR__ . '/../../reference/LOREM-IPSUM-2019-L.html';
		$this->assertSame($data, file_get_contents($filename));

		$code->disableBorder();

		$data = $output->output($code);

		$filename = __DIR__ . '/../../reference/LOREM-IPSUM-2019-L-noborder.html';
		$this->assertSame($data, file_get_contents($filename));

		$code = new QrCode('LOREM IPSUM 2019', QrCode::ERROR_CORRECTION_QUARTILE);

		$data = $output->output($code);

		$filename = __DIR__ . '/../../reference/LOREM-IPSUM-2019-Q.html';
		$this->assertSame($data, file_get_contents($filename));
	}
}
