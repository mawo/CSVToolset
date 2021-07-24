<?php
include "../src/CSVReader.php";

$csv = new \Mawo\CSVToolset\CSVReader();
$csv->load('20161103215704.csv', true);

// read all entries with its original column names:
while ($row = $csv->get())
{
    var_dump($row);
}

// reset file pointer to the start of the csv
$csv->rewind();

// only selected columns and column renaming
for ($i=0; $i<10; $i++)
{
    $csvConfig = ['#isbn' => 'isbn', 'title', 'subtitle', 'author', 'publisher'];
    var_dump($csv->get($csvConfig));
}
