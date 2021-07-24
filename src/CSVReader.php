<?php
namespace Mawo\CSVToolset;

class CSVReader
{
    protected $file = NULL;

    protected $columnNames = NULL;

    protected $hasNamedColumns = false;

    protected $separator = ',';

    /**
     * @param string $pathToCsvFile
     * @param boolean $firstLineHoldsColumnNames read column names from the first line in the csv
     * @param string $separator
     * @return boolean false on error reading file
     */
    public function load($pathToCsvFile, $firstLineHoldsColumnNames = false, $separator = ',')
    {
        if (($handle = fopen($pathToCsvFile, "r")) !== false)
        {
            $this->file = $handle;
        } else {
            return false;
        }

        $this->separator = $separator;

        // read the first line for names or to calculate number of columns
        $firstEntry = fgetcsv($this->file, 0, $this->separator);
        if ($firstEntry === false)
        {
            // error read file or empty file
            return false;
        }

        if ($firstLineHoldsColumnNames)
        {
            // initialize with column names
            $this->hasNamedColumns = true;
            $this->columnNames = $firstEntry;
        } else {
            // initialize column names as numbers
            $numberOfColumns = count($firstEntry);
            $this->columnNames = range(0, $numberOfColumns - 1);
            // reset pointer to the first entry
            $this->rewind();
        }
        return true;
    }

    /**
     * Reset file pointer to the first entry of the csv file.
     */
    public function rewind() {
        if ($this->file) {
            if ($this->hasNamedColumns)
            {
                // skip first line (column names)
                fseek($this->file, 1);
            } else {
                rewind($this->file);
            }
        }
    }

    /**
     * Get the next line of the CSV file as an array.
     * Returns false when the end of the CSV is reached.
     * Return values can be filtered by $columns. $columns is an array of column names and (optional)
     * renaming of the column name for further processing.
     * Leave $columns empty to get all values with its original names from CSV.
     *
     * Example: while ($row = $csv->get(['name', 'price', 'article#' => 'number']) { do something }
     *
     * @param array $columns filter result set to the named columns.
     * @return array|false
     */
    public function get($columns=[])
    {
        if ($this->file && ($data = fgetcsv($this->file, 0, $this->separator)) !== false)
        {
            // add column names (or index values)
            $allValues = array_combine($this->columnNames, array_values($data));
            if (!empty($columns))
            {
                // column replacements
                if ($this->hasNamedColumns)
                {
                    $outputColumns = $columns;
                    array_walk($outputColumns, function(&$nameInCsv, $replacement){
                        if (is_string($replacement))
                        {
                            $nameInCsv = $replacement;
                        }
                    });
                }
                else
                {
                    $outputColumns = array_keys($columns);
                }
                $selectedValues = [];
                foreach($outputColumns as $csvColumnName) {
                    $selectedValues[] = isset($allValues[$csvColumnName]) ? $allValues[$csvColumnName] : NULL;
                }
                $columnNames = array_values($columns);
                $selectedValues = array_combine($columnNames, $selectedValues);
            } else {
                $selectedValues = $allValues;
            }
            return $selectedValues;
        }
        return false;
    }

    /**
     * close the file and remove the file pointer
     */
    public function close()
    {
        if ($this->file !== null) {
            fclose($this->file);
        }
    }

    public function __destruct()
    {
        $this->close();
    }
}