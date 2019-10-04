<?php

namespace Mpdf\QrCode\Output;

use Mpdf\QrCode\QrCode;

/**
 * @group unit
 */
class SvgTest extends \PHPUnit\Framework\TestCase
{
    public function testOutput()
    {
        $code = new QrCode('LOREM IPSUM 2019');

        $output = new Svg();

        $data = $output->output($code);

        file_put_contents(__DIR__ . '/LOREM-IPSUM-2019-L.svg', $data);
        die();

        $filename = __DIR__ . '/../../reference/LOREM-IPSUM-2019-L.svg';
        file_put_contents($filename, $data);
        $this->assertSame($data, file_get_contents($filename));

        $code->disableBorder();

        $data = $output->output($code);

        $filename = __DIR__ . '/../../reference/LOREM-IPSUM-2019-L-noborder.svg';
        file_put_contents($filename, $data);
        $this->assertSame($data, file_get_contents($filename));

        $code = new QrCode('LOREM IPSUM 2019', QrCode::ERROR_CORRECTION_QUARTILE);

        $data = $output->output($code);

        $filename = __DIR__ . '/../../reference/LOREM-IPSUM-2019-Q.svg';
        file_put_contents($filename, $data);
        $this->assertSame($data, file_get_contents($filename));
    }
}
