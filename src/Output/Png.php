<?php

namespace Mpdf\QrCode\Output;

use Mpdf\QrCode\QrCode;

class Png
{

	/**
	 * @param \Mpdf\QrCode\QrCode QR code instance
	 * @param int QR code width in pixels
	 * @param int[] $background RGB background color
	 * @param int[] $color RGB foreground and border color
	 * @param string|null Filename of output file or empty to display directly
	 * @param int Compression level (0 - no compression, 9 - greatest compression)
	 */
	public function output(QrCode $qrCode, $w = 100, $background = [255, 255, 255], $color = [0, 0, 0], $filename = null, $quality = 0)
	{
		$qrSize = $qrCode->getQrSize();
		$final = $qrCode->getFinal();

		if ($qrCode->isBorderDisabled()) {
			$s_min = 4;
			$s_max = $qrSize - 4;
		} else {
			$s_min = 0;
			$s_max = $qrSize;
		}

		$size = $w;
		$s = $size / ($s_max - $s_min);

		// rectangle de fond
		$im = imagecreatetruecolor($size, $size);
		$c_case = imagecolorallocate($im, $color[0], $color[1], $color[2]);
		$c_back = imagecolorallocate($im, $background[0], $background[1], $background[2]);
		imagefilledrectangle($im, 0, 0, $size, $size, $c_back);

		for ($j = $s_min; $j < $s_max; $j++) {
			for ($i = $s_min; $i < $s_max; $i++) {
				if ($final[$i + $j * $qrSize + 1]) {
					imagefilledrectangle($im, ($i - $s_min) * $s, ($j - $s_min) * $s, ($i - $s_min + 1) * $s - 1, ($j - $s_min + 1) * $s - 1, $c_case);
				}
			}
		}

		if ($filename) {
			imagepng($im, $filename, $quality);
		} else {
			header('Content-type: image/png');
			imagepng($im);
		}

		imagedestroy($im);
	}

}
