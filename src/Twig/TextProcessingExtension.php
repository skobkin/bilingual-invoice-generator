<?php

namespace App\Twig;

use App\Text\PlaceholderProcessor;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class TextProcessingExtension extends AbstractExtension
{
    private PlaceholderProcessor $replacer;
    private array $translationsByLocale = [];

    /**
     * @param array $translationsByLocale ['en' => ['find' => 'replace']]
     */
    public function __construct(PlaceholderProcessor $replacer, array $translationsByLocale)
    {
        $this->replacer = $replacer;
        $this->translationsByLocale = $translationsByLocale;
    }


    public function getFilters(): array
    {
        return [
            new TwigFilter('trans', [$this, 'translate']),
            new TwigFilter('process', [$this, 'process']),
        ];
    }

    /** Translates the message if possible or returns original text. */
    public function translate(string $message, string $locale, bool $postprocess = false): string
    {
        if (!isset($this->translationsByLocale[$locale][$message])) {
            return $message;
        }

        $text = $this->translationsByLocale[$locale][$message];

        if ($postprocess) {
            $text = $this->replacer->process($text, $locale);
        }

        return $text;
    }

    public function process(string $message, string $locale): string
    {
        return $this->replacer->process($message, $locale);
    }
}
