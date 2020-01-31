<?php

namespace App\Generator;

use App\Invoice\InvoiceData;
use App\Kernel;
use App\Text\PlaceholderProcessor;
use App\Text\Replacer\{LocalizedDateReplacer, StaticReplacer, LocalizedYearMonthReplacer};
use App\Twig\{NumberToWordsExtension, TextProcessingExtension};
use Symfony\Component\Yaml\Yaml;
use Twig\Environment as Twig;
use Twig\Extra\{Html\HtmlExtension, Intl\IntlExtension};
use Twig\Loader\FilesystemLoader;

class InvoiceGenerator
{
    public static function generate(InvoiceData $invoice, array $config): string
    {
        $locales = $config['configuration']['locales'];

        $postprocessor = static::createPostprocessor($invoice);

        $twig = static::createTwig($postprocessor, $locales['source'], $locales['target']);

        $images = static::getImagesContent($config['configuration']['images']);

        return $twig->render('invoice.html.twig', [
            'locales' => $locales,
            'currency' => $config['configuration']['currency_code'],
            'services' => $config['services'],
            'images' => $images,
        ]);
    }

    private static function getImagesContent(array $paths): array
    {
        $new = [];

        foreach ($paths as $key => $path) {
            $new[$key] = file_get_contents(Kernel::getProjectRoot().'/'.$path);
        }

        return $new;
    }

    private static function createTwig(
        PlaceholderProcessor $placeholderProcessor,
        string $sourceLocale,
        string $targetLocale
    ): Twig {
        $loader = new FilesystemLoader(Kernel::getProjectRoot().'/templates');
        $twig = new Twig($loader);

        $twig->addExtension(new NumberToWordsExtension());
        $twig->addExtension(new IntlExtension());
        $twig->addExtension(new HtmlExtension());

        $localeData = static::loadLocaleData($sourceLocale, $targetLocale);
        $twig->addExtension(new TextProcessingExtension($placeholderProcessor, $localeData));

        return $twig;
    }

    private static function createPostprocessor(InvoiceData $invoice): PlaceholderProcessor
    {
        $processor = new PlaceholderProcessor();

        $contractDate = new LocalizedDateReplacer($invoice->getContractStartDate());
        $processor->addReplacer('%contract_date%', $contractDate);

        $invoiceNumber = new StaticReplacer($invoice->getNumber());
        $processor->addReplacer('%invoice_number%', $invoiceNumber);

        $invoiceDate = new LocalizedDateReplacer($invoice->getIssueDate());
        $processor->addReplacer('%invoice_date%', $invoiceDate);

        $periodEnd = new LocalizedYearMonthReplacer($invoice->getAccountedMonthDate());
        $processor->addReplacer('%period_end_date%', $periodEnd);

        return $processor;
    }

    private static function loadLocaleData(string $source, string $target): array
    {
        return [
            $source => Yaml::parseFile(Kernel::getProjectRoot().'/translation/source.yaml'),
            $target => Yaml::parseFile(Kernel::getProjectRoot().'/translation/target.yaml'),
        ];
    }
}
