<?php

namespace Mpdf\QrCode\Output;

use Mpdf\QrCode\QrCode;
use SimpleXMLElement;

class Svg
{
    /**
     * We need a little bit of overlap of each rectangle
     * because otherwise you'll start to see fine lines between each rectangle.
     *
     * @var float
     */
    protected $overlap = 0.02;

    /**
     * @return float
     */
    public function getOverlap()
    {
        return $this->overlapPercent;
    }

    /**
     * @param float $overlap
     */
    public function setOverlap($overlap)
    {
        $this->overlap = $overlap;
    }

    /**
     * @param QrCode $qrCode     QR code instance
     * @param int    $size       The width / height of the resulting SVG
     * @param string $background The background color, e. g. "white", "rgb(0,0,0)" or "cmyk(0,0,0,0)"
     * @param string $color      The foreground and border color, e. g. "black", "rgb(255,255,255)" or "cmyk(0,0,0,100)"
     *
     * @return string Binary image data
     */
    public function output(QrCode $qrCode, $size = 100, $background = 'white', $color = 'black')
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

        $rectSize = $size / ($maxSize - $minSize);

        $svg = new SimpleXMLElement('<svg></svg>');
        $svg->addAttribute('version', '1.1');
        $svg->addAttribute('xmlns', 'http://www.w3.org/2000/svg');
        $svg->addAttribute('width', $size);
        $svg->addAttribute('height', $size);

        $this->addChild(
            $svg,
            'rect',
            [
                'x'      => 0,
                'y'      => 0,
                'width'  => $size,
                'height' => $size,
                'fill'   => $background,
            ]
        );

        for ($row = $minSize; $row < $maxSize; $row++) {
            // Simple compression: pixels in a row will be compressed into the same rectangle.
            $startX = null;
            for ($column = $minSize; $column < $maxSize; $column++) {
                if ($final[$column + $row * $qrSize + 1]) {
                    if ($startX === null) {
                        $startX = ($column - $minSize - $this->overlap) * $rectSize;
                    }
                } elseif ($startX !== null) {
                    $this->addChild(
                        $svg,
                        'rect',
                        [
                            'x'      => $startX,
                            'y'      => ($row - $minSize - $this->overlap) * $rectSize,
                            'width'  => ($column - $minSize + $this->overlap) * $rectSize - $startX,
                            'height' => (1 + $this->overlap) * $rectSize,
                            'fill'   => $color,
                        ]
                    );
                    $startX = null;
                }
            }

            if ($startX !== null) {
                $this->addChild(
                    $svg,
                    'rect',
                    [
                        'x'      => $startX,
                        'y'      => ($row - $minSize - $this->overlap) * $rectSize,
                        'width'  => ($column - $minSize + $this->overlap) * $rectSize - $startX,
                        'height' => (1 + $this->overlap) * $rectSize,
                        'fill'   => $color,
                    ]
                );
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
