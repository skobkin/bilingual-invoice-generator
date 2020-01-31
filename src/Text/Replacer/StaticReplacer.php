<?php

namespace App\Text\Replacer;

class StaticReplacer implements ReplacerInterface
{
    private string $replace;

    public function __construct(string $replace)
    {
        $this->replace = $replace;
    }

    public function generateReplace(string $locale): string
    {
        return $this->replace;
    }
}
