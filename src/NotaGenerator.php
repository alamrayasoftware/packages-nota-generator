<?php

namespace ArsoftModules\NotaGenerator;

use Illuminate\Support\Facades\DB;

class NotaGenerator
{
    private $result, $tableName, $columnName, $counterLength, $date, $prefix, $prefixSeparator;

    public function __construct()
    {
        $this->result = "";
    }

    /**
     * set number preset
     * 
     * @param string $tableName
     * @param string $columnName
     * @param int $counterLength digits length of counter, ex : 001 -> 3 digits, 00001 -> 5 digits
     * @param string|null $date format: Y/m/d || Carbon::class
     * 
     * @return this
     */
    public function generate(
        string $tableName,
        string $columnName,
        int $counterLength,
        $date = null
    ) {
        $this->tableName = $tableName;
        $this->columnName = $columnName;
        $this->counterLength = $counterLength;
        $this->date = $date;
        return $this;
    }

    /**
     * add prefix to number
     * 
     * @param string $prefix example: 'PRO', 'ORDERS', 'NOTA', etc
     * @param string $separator default: '-', example: '-', '/', '.', etc
     * 
     * @return this
     */
    public function addPrefix(string $prefix, string $separator = '-')
    {
        $this->prefix = $prefix;
        $this->prefixSeparator = $separator;
        return $this;
    }

    /**
     * get generated nota
     * 
     * @return string eg: PRO/2021/12/23/0001, PRO/0001
     */
    public function getResult()
    {
        if ($this->date) {
            $this->generateNumberWithDate();
        } else {
            $this->generateNumberWithoutDate();
        }

        if ($this->prefix) {
            $this->result = $this->prefix . $this->prefixSeparator . $this->result;
        }

        return $this->result;
    }

    // generate number with date
    private function generateNumberWithDate()
    {
        $date = now()->parse($this->date)->format('Y/m/d');
        $prefixNumber = $date;
        if ($this->prefix) {
            $prefixNumber = $this->prefix . $this->prefixSeparator . $date;
        }

        // get increment number
        $newNumber = $this->getNextNumber($prefixNumber);

        $this->result = $date . '/' . str_pad($newNumber, $this->counterLength, '0', STR_PAD_LEFT);
    }

    // generate number without date
    private function generateNumberWithoutDate()
    {
        $prefixNumber = null;
        if ($this->prefix) {
            $prefixNumber = $this->prefix . $this->prefixSeparator;
        }

        // get increment number
        $newNumber = $this->getNextNumber($prefixNumber);

        $this->result = str_pad($newNumber, $this->counterLength, '0', STR_PAD_LEFT);
    }

    private function getNextNumber(string $prefixNumber)
    {
        // get latest number in database
        $lastNota = DB::table($this->tableName)
            ->where($this->columnName, 'like', '%' . $prefixNumber . '%')
            ->latest($this->columnName)
            ->first();

        // increment the number
        $newNumber = 1;
        if ($lastNota) {
            $columnName = $this->columnName;
            $lastNota = $lastNota->$columnName;
            $lastNumber = (int) substr($lastNota, ($this->counterLength * -1));
            $newNumber = $lastNumber + 1;
        }

        return $newNumber;
    }
}
