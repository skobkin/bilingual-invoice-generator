<?php

namespace App\Text;

use App\Text\Replacer\ReplacerInterface;

class PlaceholderProcessor
{
    /** @var ReplacerInterface[] */
    private array $replacers = [];

    private array $replaceCache = [];

    /**
     * @param array|callable[] $replacers
     */
    public function __construct(array $replacers = [])
    {
        $this->replacers = $replacers;
    }

    public function addReplacer(string $placeholder, ReplacerInterface $replacer): void
    {
        if (array_key_exists($placeholder, $this->replacers)) {
            throw new \RuntimeException('Handler for \''.$placeholder.'\' already exists.');
        }

        $this->replacers[$placeholder] = $replacer;
    }

    public function process(
        string $string,
        string $locale
    ): string {
        foreach ($this->replacers as $placeholder => $replacer) {
            if (!isset($this->replaceCache[$locale][$placeholder])) {
                $this->replaceCache[$locale][$placeholder] = $replacer->generateReplace($locale);
            }

            $string = str_replace(
                $placeholder,
                $this->replaceCache[$locale][$placeholder],
                $string
            );
        }

        return $string;
    }
}
