<?php

namespace ArsoftModules\NotaGenerator;

use Illuminate\Support\Facades\DB;

class NotaGenerator
{

    private $result;

    public function __construct()
    {
        $this->result = "";
    }

    /**
     * generate nota
     * 
     * @param string $tableName
     * @param string $columnName
     * @param int $counterLength digits length of counter, ex : 001 -> 3 digits, 00001 -> 5 digits
     * @param string|carbon|null $date format: Y/m/d
     * 
     * @return string eg: PRO/2021/12/23/0001, PRO/0001
     */
    public function generate(
        string $tableName,
        string $columnName,
        int $counterLength,
        string $date = null
    ) {
        $startCounter = ($counterLength + 11) * -1;
        if ($date) {
            $date = now()->parse($date)->format('Y/m/d');
            $lastNota = DB::table($tableName)
                ->whereRaw('substr(' . $columnName . ', ' . $startCounter . ', 10) = "' . $date . '"')
                ->latest($columnName)
                ->first();
        } else {
            $lastNota = DB::table($tableName)
                ->latest($columnName)
                ->first();
        }

        if (!is_null($lastNota)) {
            $lastNota = $lastNota->$columnName;
            $lastNumber = (int) substr($lastNota, ($counterLength * -1));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        if (!$date) {
            $this->result = str_pad($newNumber, $counterLength, '0', STR_PAD_LEFT);
        } else {
            $this->result = $date . '/' . str_pad($newNumber, $counterLength, '0', STR_PAD_LEFT);
        }

        return $this;
    }

    /**
     * add prefix to nota
     * 
     * @param string $prefix example: 'PRO', 'ORDERS', 'NOTA', etc
     * @param string $separator example: '-', '/', '.', etc
     */
    public function addPrefix(string $prefix, string $separator = '-')
    {
        $this->result = $prefix . $separator . $this->result;
        return $this;
    }

    /**
     * get generated nota
     */
    public function getResult()
    {
        return $this->result;
    }
}
