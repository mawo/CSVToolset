<?php
include "../src/CSVReader.php";

$csv = new \Mawo\CSVToolset\CSVReader();
$csv->load('schedule.csv', false);

// read all entries and create named columns:
while ($row = $csv->get())
{
    // column renaming
    $columnNames = ['title', 'start', 'end', 'weekday'];
    $row = array_combine($columnNames, $row);
    var_dump($row);
}

// fetch only some columns (and name them by the way):
$csv->rewind();
while ($row = $csv->get([0 => 'title', 3 => 'weekday']))
{
    // column renaming
    var_dump($row);
}
