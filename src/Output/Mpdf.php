<?php

namespace Mpdf\QrCode\Output;

use Mpdf\Mpdf as MpdfObject;
use Mpdf\QrCode\QrCode;

class Mpdf
{

	/**
	 * Write the QR code into an Mpdf\Mpdf object
	 *
	 * @param \Mpdf\QrCode\QrCode $qrCode QR code instance
	 * @param \Mpdf\Mpdf $mpdf Mpdf instance
	 * @param float $x position X
	 * @param float $y position Y
	 * @param float $w QR code width
	 * @param int[] $background RGB/CMYK background color
	 * @param int[] $color RGB/CMYK foreground and border color
	 */
	public function output(QrCode $qrCode, MpdfObject $mpdf, $x, $y, $w, $background = [255, 255, 255], $color = [0, 0, 0])
	{
		$size = $w;
		$qrSize = $qrCode->getQrSize();
		$s = $size / $qrCode->getQrDimensions();

        $background = array_slice($background, 0, 4);
        $color = array_slice($color, 0, 4);

		$mpdf->SetDrawColor(...$color);
		$mpdf->SetFillColor(...$background);

		if ($qrCode->isBorderDisabled()) {
			$minSize = 4;
			$maxSize = $qrSize - 4;
			$mpdf->Rect($x, $y, $size, $size, 'F');
		} else {
			$minSize = 0;
			$maxSize = $qrSize;
			$mpdf->Rect($x, $y, $size, $size, 'FD');
		}

		$mpdf->SetFillColor(...$color);

		$final = $qrCode->getFinal();

		for ($j = $minSize; $j < $maxSize; $j++) {
			for ($i = $minSize; $i < $maxSize; $i++) {
				if ($final[$i + $j * $qrSize + 1]) {
					$mpdf->Rect($x + ($i - $minSize) * $s, $y + ($j - $minSize) * $s, $s, $s, 'F');
				}
			}
		}
	}

}
