# CSV Toolset

## CSV Reader
Class `CSVReader` is a wrapper around the php function `fgetcsv`.
Purpose of the class is easy access to comma separated value lists (CSV Files) identified by column labels.

**Usage Example:**
```PHP
$csv = new \Mawo\CSVToolset\CSVReader();
$csv->load('example.csv', true);

// read all entries with its original column names:
while ($row = $csv->get())
{
    var_dump($row);
}

// reset file pointer to the start of the csv
$csv->rewind();

// column renaming
for ($i=0; $i<10; $i++)
{
    $csvConfig = ['#isbn' => 'isbn', 'title', 'subtitle', 'author', 'publisher'];
    var_dump($csv->get($csvConfig));
}
```

If you don't have named columns you can still use the wrapper:

**Example**

```PHP
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
```

## Missing feature?
Drop me a note about your suggestion or fork the repo and create a merge request.
