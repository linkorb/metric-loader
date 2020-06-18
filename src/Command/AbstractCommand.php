<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\String\Slugger\AsciiSlugger;
use DateTime;
use DateInterval;

abstract class AbstractCommand extends Command
{
    protected function removeBom(string $text): string
    {
        $bom = pack('H*','EFBBBF');
        $text = preg_replace("/^$bom/", '', $text);
        // $bom = pack('H*','FFFE');
        // $text = preg_replace("/^$bom/", '', $text);
        return $text;
    }

    protected function outputEntries($output, $entries): void
    {
        $output->writeLn("metric,period,dimension,value,note");
        foreach ($entries as $entry) {
            $output->writeLn($entry['metric'] . "," . $entry['period'] . ',' . $entry['dimension'] . ',' . $entry['value'] . ',' . $entry['notes']) ;
        }
    }

    protected function rows2entries(array $rows, string $metricKeyPrefix, array $metricKeys): array
    {
        $entries = [];
        foreach ($rows as $row) {
            $period = $row['date'];
            foreach ($metricKeys as $metricKey) {
                $value = $row[$metricKey] ?? null;
                if ($value) {
                    $entries[] = [
                        'metric' => $metricKeyPrefix . $metricKey,
                        'period' => $period,
                        'dimension' => null,
                        'value' => $value,
                        'notes' => null,
                    ];
                }
            }
        }
        return $entries;
    }
}
