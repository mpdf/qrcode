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
			$s_min = 4;
			$s_max = $qrSize - 4;
		} else {
			$s_min = 0;
			$s_max = $qrSize;
		}

		$s .= '<table class="qr" cellpadding="0" cellspacing="0">' . "\n";

		for ($y = $s_min; $y < $s_max; $y++) {
			$s .= '<tr>';
			for ($x = $s_min; $x < $s_max; $x++) {
				$s .= '<td class="' . ($final[$x + $y * $qrSize + 1] ? 'on' : 'off') . '"></td>';
			}
			$s .= '</tr>' . "\n";
		}

		$s .= '</table>';

		return $s;
	}

}
