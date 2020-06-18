<?php

namespace App\Command;

use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\String\Slugger\AsciiSlugger;
use DateTime;
use DateInterval;

class MatomoCommand extends AbstractCommand
{
    protected function configure()
    {
        $this
            ->setName('matomo')
            ->setDescription('Import matomo metrics')
            ->addArgument(
                'filename',
                InputArgument::REQUIRED,
                'Input filename'
            )
            ->addOption(
                'prefix',
                'p',
                InputOption::VALUE_REQUIRED,
                'Output key prefix',
                null
            )
            ->addOption(
                'keys',
                'k',
                InputOption::VALUE_REQUIRED,
                'Only extract listed keys',
                null
            )
        ;
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $filename = $input->getArgument('filename');
        $metricKeyPrefix = trim($input->getOption('prefix'), '. ');
        if ($metricKeyPrefix) {
            $metricKeyPrefix .= '.';
        }
        $metricKeys = [];
        if ($input->getOption('keys')) {
            $metricKeys = explode(",", $input->getOption('keys'));
        }

        if (!file_exists($filename)) {
            throw new RuntimeException("File not found: " . $filename);
        }

        $content = file_get_contents($filename);
        $content = $this->removeBom($content);
        $content = mb_convert_encoding($content, "utf-8", 'utf-16');
        $lines = explode("\n", $content);
        $rows = array_map('str_getcsv', $lines);
        $slugger = new AsciiSlugger();
        $header = array_shift($rows);
        foreach ($header as $i=>$name) {
            $name = $slugger->slug($name);
            $header[$i] = (string)strtolower($name);
        }
        if (count($metricKeys)==0) {
            $metricKeys = $header;
        }
        // print_r($metricKeys); exit();
        $data = array();
        foreach($rows as $row) {
            if (count($row)==count($header)) {
                $data[] = array_combine($header, $row);
            }
        }
        // print_r($data);

        $entries = $this->rows2entries($data, $metricKeyPrefix, $metricKeys);

        $this->outputEntries($output, $entries);
        return 0;
    }


}
