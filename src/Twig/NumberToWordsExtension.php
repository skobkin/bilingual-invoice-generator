<?php

namespace App\Twig;

use NumberToWords\NumberToWords;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class NumberToWordsExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('ntw', [$this, 'numberToWords'])
        ];
    }

    public function numberToWords(int $number, string $locale): string
    {
        $ntw = new NumberToWords();
        return $ntw->getNumberTransformer($locale)->toWords($number);
    }
}
