<?php

namespace App\Text\Replacer;

class LocalizedDateReplacer implements ReplacerInterface
{
    private \DateTime $date;

    public function __construct(\DateTime $date = null)
    {
        $this->date = $date ?? new \DateTime();
    }

    public function generateReplace(string $locale): string
    {
        $formatter = new \IntlDateFormatter($locale, \IntlDateFormatter::LONG, \IntlDateFormatter::LONG);
        $formatter->setPattern('d MMMM yyyy');

        return $formatter->format($this->date);
    }
}
