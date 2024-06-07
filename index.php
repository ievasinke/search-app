<?php

require_once 'vendor/autoload.php';

use GuzzleHttp\Client;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableCell;
use Symfony\Component\Console\Helper\TableCellStyle;
use Symfony\Component\Console\Output\ConsoleOutput;


$input = (string)readline("Enter company name: ");

$searchQuery = urlencode($input);
$resource_id = "25e80bf3-f107-4ab4-89ef-251b5b9374e9";
$url = "https://data.gov.lv/dati/lv/api/3/action/datastore_search?q=$searchQuery&resource_id=$resource_id";


$client = new Client();
$response = $client->request('GET', $url);

$companies = $response->getBody();
$companies = json_decode($companies);

$records = $companies->result->records;

$outputTasks = new ConsoleOutput();
$table = new Table($outputTasks);

$table->setHeaderTitle('Companies');
$table->render();
$table
    ->setHeaders(['Registration number', 'Name', 'Type', 'Registered at', 'Address'])
    ->setRows(array_map(function (stdClass $record): array {
        return [
            $record->sepa,
            new TableCell($record->name, ['style' => new TableCellStyle(['align' => 'center',])]),
            $record->type,
            $record->registered,
            $record->address,
        ];
    }, $records));
$table->setStyle('box-double');
$table->render();
