<?php

namespace Mpdf\QrCode\Output;

use Mpdf\QrCode\QrCode;
use SimpleXMLElement;

class Svg
{
    /**
     * @param QrCode $qrCode     QR code instance
     * @param int[]  $background RGB background color
     * @param int[]  $color      RGB foreground and border color
     *
     * @return string Binary image data
     */
    public function output(QrCode $qrCode, $background = [255, 255, 255], $color = [0, 0, 0])
    {
        $qrSize = $qrCode->getQrSize();
        $final  = $qrCode->getFinal();

        if ($qrCode->isBorderDisabled()) {
            $minSize = 4;
            $maxSize = $qrSize - 4;
        } else {
            $minSize = 0;
            $maxSize = $qrSize;
        }

        $rectSize = 1;

        $svg = new SimpleXMLElement('<svg></svg>');
        $svg->addAttribute('version', '1.1');
        $svg->addAttribute('xmlns', 'http://www.w3.org/2000/svg');
        $svg->addAttribute('width', $qrSize);
        $svg->addAttribute('height', $qrSize);

        $this->addChild(
            $svg,
            'rect',
            [
                'x'      => 0,
                'y'      => 0,
                'width'  => $qrSize,
                'height' => $qrSize,
                'fill'   => sprintf(
                    'rgb(%d, %d, %d)',
                    $background[0],
                    $background[1],
                    $background[2]
                ),
            ]
        );

        $foregroundColor = sprintf(
            'rgb(%d, %d, %d)',
            $color[0],
            $color[1],
            $color[2]
        );

        for ($row = $minSize; $row < $maxSize; $row++) {
            for ($column = $minSize; $column < $maxSize; $column++) {
                if ($final[$column + $row * $qrSize + 1]) {
                    $this->addChild(
                        $svg,
                        'rect',
                        [
                            'x'      => ($column - $minSize) * $rectSize,
                            'y'      => ($row - $minSize) * $rectSize,
                            'width'  => $rectSize,
                            'height' => $rectSize,
                            'fill'   => $foregroundColor,
                        ]
                    );
                }
            }
        }

        return $svg->asXML();
    }


    /**
     * Adds a child with the given attributes
     *
     * @param SimpleXMLElement $svg
     * @param string           $name
     * @param array            $attributes
     *
     * @return SimpleXMLElement
     */
    public function addChild(SimpleXMLElement $svg, $name, array $attributes = [])
    {
        $child = $svg->addChild($name);

        foreach ($attributes as $key => $value) {
            $child->addAttribute((string) $key, (string) $value);
        }

        return $child;
    }
}
