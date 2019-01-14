<?php

namespace Mpdf\QrCode\Output;

use Mpdf\Mpdf as MpdfObject;
use Mpdf\QrCode\QrCode;

class Mpdf
{

	/**
	 * Write the QR code into an Mpdf\Mpdf object
	 *
	 * @param \Mpdf\QrCode\QrCode QR code instance
	 * @param \Mpdf\Mpdf $mpdf Mpdf instance
	 * @param float $x position X
	 * @param float $y position Y
	 * @param float $w QR code width
	 * @param int[] $background RGB background color
	 * @param int[] $color RGB foreground and border color
	 */
	public function output(QrCode $qrCode, MpdfObject $mpdf, $x, $y, $w, $background = [255, 255, 255], $color = [0, 0, 0])
	{
		$size = $w;
		$qrSize = $qrCode->getQrSize();
		$s = $size / $qrSize;

		$mpdf->SetDrawColor($color[0], $color[1], $color[2]);
		$mpdf->SetFillColor($background[0], $background[1], $background[2]);

		// rectangle de fond
		if ($qrCode->isBorderDisabled()) {
			$s_min = 4;
			$s_max = $qrSize - 4;
			$mpdf->Rect($x, $y, $size, $size, 'F');
		} else {
			$s_min = 0;
			$s_max = $qrSize;
			$mpdf->Rect($x, $y, $size, $size, 'FD');
		}

		$mpdf->SetFillColor($color[0], $color[1], $color[2]);

		$final = $qrCode->getFinal();

		for ($j = $s_min; $j < $s_max; $j++) {
			for ($i = $s_min; $i < $s_max; $i++) {
				if ($final[$i + $j * $qrSize + 1]) {
					$mpdf->Rect($x + ($i - $s_min) * $s, $y + ($j - $s_min) * $s, $s, $s, 'F');
				}
			}
		}
	}

}
