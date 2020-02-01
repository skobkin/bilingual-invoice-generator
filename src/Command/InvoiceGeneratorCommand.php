<?php

namespace App\Command;

use App\Generator\InvoiceGenerator;
use App\Invoice\InvoiceData;
use App\Kernel;
use Asika\SimpleConsole\{CommandArgsException, Console};
use Mpdf\Mpdf;
use Symfony\Component\Yaml\Yaml;

class InvoiceGeneratorCommand extends Console
{
    private const OUTPUT_NAME_TEMPLATE = 'invoice_%d_%s.pdf';

    protected $help = <<<HELP
[Usage] generate <number> <path>

[Arguments]
    number           Invoice number
    path             Can be directory or file name. File name will be generated if directory is used.

[Options]
    i | issue-date   Invoice issue date (format: '20.10.2040')
    a | month-date   Accounted month date (format: '20.10.2040')
    
    h | help         Show help information
    v                Show more debug information.
HELP;

    protected function doExecute(): int
    {
        $number = $this->getArgument(0);
        $path = $this->getArgument(1);
        if (!$number) {
            throw new CommandArgsException('Invoice number is mandatory.');
        }
        if (!$path) {
            throw new CommandArgsException('Path is mandatory.');
        }

        $config = Yaml::parseFile(Kernel::getProjectRoot().'/config/parameters.yaml');

        $now = new \DateTime();

        $issueDate = $this->getOption(['i', 'issue-date'], $now->format('d.m.y'));
        $accountedMonthDate = $this->getOption(['a', 'month-date'], $issueDate);

        $invoice = new InvoiceData(
            (int) $this->getArgument(0),
            new \DateTime($issueDate),
            new \DateTime($config['contract']['date_from']),
            new \DateTime($accountedMonthDate)
        );

        $html = InvoiceGenerator::generate($invoice, $config);

        $mpdf = new Mpdf();
        $mpdf->WriteHTML($html);

        if (is_dir($path)) {
            $filePath = realpath($path).'/'.sprintf(
                self::OUTPUT_NAME_TEMPLATE,
                $number,
                    $invoice->getIssueDate()->format('d_m_y')
            );
        } else {
            $filePath = $path;
        }

        $mpdf->Output($filePath, 'F');

        $this->out($filePath.' exported.');

        return 0;
    }
}
