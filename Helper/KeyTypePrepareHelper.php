<?php

class KeyTypePrepareHelper
{
    protected $keyTypesArray
        = [
            'PRIMARY'    => 'primary',
            'UNIQUE'     => 'uniqie',
            'CONSTRAINT' => 'foreign',
            'KEY'        => 'index',
        ];

    public function getKeyTypesArray()
    {
        return $this->keyTypesArray;
    }

    public function preparePrimaryKey($line)
    {
        preg_match("~PRIMARY KEY \(`(.+)`\)~", $line, $match);
        return '<constraint xsi:type="primary" referenceId="PRIMARY"> ' . "\n" .
            '<column name="' . $match[1] . '"/>' . "\n" .
            '</constraint>' . "\n";
    }

    public function prepareUniqueKey($line)
    {
        $keyNode = '<constraint xsi:type="unique" referenceId="';
        preg_match('~UNIQUE KEY `(.+)` \((.+)\)~', $line, $match);
        $keyNode .= $match[1] . '">' . "\n";
        $columns = explode(',', $match[2]);
        foreach ($columns as $column) {
            $keyNode .= '<column name="' . str_replace('`', '', $column) . '" />' . "\n";
        }
        $keyNode .= '</constraint>' . "\n";
        return $keyNode;
    }

    public function prepareForeignKey($line, $tableName)
    {
        preg_match('~CONSTRAINT `(.+)` FOREIGN KEY \(`(.+)`\) ' .
            'REFERENCES `(.+)` \(`(.+)`\) ON DELETE ([^ ]+) ~', $line, $match);
        return '<constraint xsi:type="foreign" referenceId="' .
            $match[1] . '" table="' . $tableName . '" ' . 'column="' .
            $match[2] . '" referenceTable="' .
            $match[3] . '" referenceColumn="' .
            $match[4] . '" onDelete="' . $match[5] . '" />' . "\n";
    }

    public function prepareIndexKey($line)
    {
        preg_match("~KEY `(.+)` \(`(.+)`~", $line, $match);
        return '<index referenceId="' . $match[1] . '">' . "\n" .
            '<column name="' . $match[2] . '"/>' . "\n" .
            '</index>' . "\n";
    }
}