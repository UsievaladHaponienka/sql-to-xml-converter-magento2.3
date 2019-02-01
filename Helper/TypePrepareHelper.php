<?php


class TypePrepareHelper
{
    protected $typesArray
        = [
            'smallint\(([0-9]+)\)'         => 'smallint',
            'bigint\(([0-9]+)\)'           => 'bigint',
            'tinyint\(([0-9]+)\)'          => 'tinyint',
            'int\(([0-9]+)\)'              => 'int',
            'decimal\(([0-9]+),([0-9]+)\)' => 'decimal',
            'float\(([0-9]+),([0-9]+)\)'   => 'float',
            'double\(([0-9]+),([0-9]+)\)'  => 'double',
            'char\(([0-9]+)\)'             => 'varchar',
            'boolean'                      => 'boolean',
            'timestamp'                    => 'timestamp',
            'datetime'                     => 'datetime',
            'date'                         => 'date',
            'longblob'                     => 'longblob',
            'mediumblob'                   => 'mediumblob',
            'blob'                         => 'blob',
            'longtext'                     => 'longtext',
            'mediumtext'                   => 'mediumtext',
            'text'                         => 'text',
        ];

    public function getTypesArray()
    {
        return $this->typesArray;
    }

    public function prepareSmallInt($line)
    {
        preg_match("~smallint\(([0-9]+)\)~i", $line, $match);
        return 'smallint" padding="' . $match[1] . '" ';
    }

    public function prepareBigInt($line)
    {
        preg_match("~bigint\(([0-9]+)\)~i", $line, $match);
        return 'bigint" padding="' . $match[1] . '" ';
    }

    public function prepareTinyInt($line)
    {
        preg_match("~tinyint\(([0-9]+)\)~i", $line, $match);
        return 'tinyint" padding="' . $match[1] . '" ';
    }

    public function prepareInt($line)
    {
        preg_match("~int\(([0-9]+)\)~i", $line, $match);
        return 'int" padding="' . $match[1] . '" ';
    }

    public function prepareDecimal($line)
    {
        preg_match("~decimal\(([0-9]+),([0-9]+)\)~i", $line, $match);
        return 'decimal" precision="' . $match[1] . '" scale="' . $match[2] . '" ';
    }

    public function prepareFloat($line)
    {
        preg_match("~float\(([0-9]+),([0-9]+)\)~i", $line, $match);
        return 'float" scale="' . $match[1] . '" precision="' . $match[2] . '" ';
    }

    public function prepareDouble($line)
    {
        preg_match("~double\(([0-9]+),([0-9]+)\)~i", $line, $match);
        return 'double" scale="' . $match[1] . '" precision="' . $match[2] . '" ';
    }

    public function prepareVarchar($line)
    {
        preg_match("~char\(([0-9]+)\)~i", $line, $match);
        return 'varchar" length="' . $match[1] . '" ';
    }

    public function prepareBoolean()
    {
        return 'boolean" ';
    }

    public function prepareTimestamp($line)
    {
        $timestampStr = 'timestamp" ';
        if (preg_match('~ON UPDATE CURRENT_TIMESTAMP~', $line)) {
            $timestampStr .= 'on_update="true" ';
        }
        return $timestampStr;
    }
}