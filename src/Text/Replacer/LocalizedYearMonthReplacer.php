<?php

namespace App\Text\Replacer;

class LocalizedYearMonthReplacer implements ReplacerInterface
{
    private \DateTime $date;

    public function __construct(\DateTime $dateInMonth = null)
    {
        $this->date = $dateInMonth ?? new \DateTime();
    }

    public function generateReplace(string $locale): string
    {
        $formatter = new \IntlDateFormatter($locale, \IntlDateFormatter::LONG, \IntlDateFormatter::LONG);
        $formatter->setPattern('LLLL yyyy');

        return $formatter->format($this->date);
    }
}
