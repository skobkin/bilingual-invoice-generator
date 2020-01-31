<?php

namespace App\Text\Replacer;

interface ReplacerInterface
{
    public function generateReplace(string $locale): string;
}
