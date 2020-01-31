<?php

namespace App\Generator;

use App\Kernel;
use App\Twig\NumberToWordsExtension;
use App\Util\StringReplacer;
use Twig\Environment as Twig;
use Twig\Extra\Html\HtmlExtension;
use Twig\Loader\FilesystemLoader;

class InvoiceGenerator
{
    public static function generate(array $config): string
    {
        $twig = static::createTwig();

        $sourceStaticSubstitutions = $config['translations']['source']['static_substitutions'];
        //$sourceStaticSubstitutions = $config['translations']['target']['static_substitutions'];
        $source = StringReplacer::recursiveReplace(
            $config['translations']['source']['variables'],
            $sourceStaticSubstitutions
        );
        //$target = StringReplacer::recursiveReplace($config['translations']['target']['variables']);

        // @TODO fix multilingual substitution depending on the context
        $services = StringReplacer::recursiveReplace($config['services'], $sourceStaticSubstitutions);
        $images = static::getImagesContent($config['configuration']['images']);

        return $twig->render('invoice.html.twig', [
            'configuration' => $config['configuration'],
            'trans_data' => [
                'source' => $source,
                //'target' => $target,
            ],
            'services' => $services,
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

    private static function createTwig(): Twig
    {
        $loader = new FilesystemLoader(__DIR__.'/../../templates');
        $twig = new Twig($loader);

        $twig->addExtension(new NumberToWordsExtension());
        $twig->addExtension(new HtmlExtension());

        return $twig;
    }
}