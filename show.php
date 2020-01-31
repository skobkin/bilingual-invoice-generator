<?php

require_once __DIR__.'/vendor/autoload.php';

use App\Generator\InvoiceGenerator;
use Mpdf\Mpdf;
use Symfony\Component\Yaml\Yaml;

$config = Yaml::parseFile(__DIR__.'/config/parameters.yaml');

$pdf = $_GET['pdf'] ?? false;

if ($pdf) {
    $mpdf = new Mpdf();
    $mpdf->WriteHTML(InvoiceGenerator::generate($config));
    $mpdf->Output();
} else {
    echo InvoiceGenerator::generate($config);
}
