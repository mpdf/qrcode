<?php

namespace Mpdf\QrCode\Output;

use Mpdf\QrCode\QrCode;

class Html
{

	/**
	 * @param \Mpdf\QrCode\QrCode $qrCode
	 *
	 * @return string
	 */
	public function output(QrCode $qrCode)
	{
		$s = '';

		$qrSize = $qrCode->getQrSize();
		$final = $qrCode->getFinal();

		if ($qrCode->isBorderDisabled()) {
			$minSize = 4;
			$maxSize = $qrSize - 4;
		} else {
			$minSize = 0;
			$maxSize = $qrSize;
		}

		$s .= '<table class="qr" cellpadding="0" cellspacing="0">' . "\n";

		for ($y = $minSize; $y < $maxSize; $y++) {
			$s .= '<tr>';
			for ($x = $minSize; $x < $maxSize; $x++) {
				$s .= '<td class="' . ($final[$x + $y * $qrSize + 1] ? 'on' : 'off') . '"></td>';
			}
			$s .= '</tr>' . "\n";
		}

		$s .= '</table>';

		return $s;
	}

}
