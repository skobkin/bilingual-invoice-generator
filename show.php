<?php

require_once __DIR__.'/vendor/autoload.php';

use App\Generator\InvoiceGenerator;
use App\Invoice\InvoiceData;
use App\Kernel;
use Mpdf\Mpdf;
use Symfony\Component\Yaml\Yaml;

$config = Yaml::parseFile(Kernel::getProjectRoot().'/config/parameters.yaml');

$invoice = new InvoiceData(
    123,
    new \DateTime(),
    new \DateTime($config['contract']['date_from']),
    new \DateTime
);

$pdf = $_GET['pdf'] ?? false;

if ($pdf) {
    $mpdf = new Mpdf();
    $mpdf->WriteHTML(InvoiceGenerator::generate($invoice, $config));
    $mpdf->Output();
} else {
    echo InvoiceGenerator::generate($invoice, $config);
}
