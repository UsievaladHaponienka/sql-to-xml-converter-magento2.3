<?php

class fullXml
{
    public $tableNode;

    public $columnNodes = [];

    public $tableComment;

    public function setTableNode($node)
    {
        $this->tableNode = $node;
    }

    public function setTableComment($comment)
    {
        $this->tableNode .= $comment . "\n";
    }

    public function addColumnNode($node)
    {
        $this->columnNodes[] = $node;
    }

    public function removeFile()
    {
        unlink('XML/text.xml');
    }

    public function initXml()
    {
        $file = fopen('XML/text.xml', 'a');
        fwrite($file, '<?xml version="1.0"?>' . PHP_EOL);
        fwrite($file,
            '<schema xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="urn:magento:framework:Setup/Declaration/Schema/etc/schema.xsd">'
            . PHP_EOL);
        fclose($file);
    }

    public function writeXml()
    {
        $xml = $this->tableNode;
        foreach ($this->columnNodes as $columnNode) {
            $xml .= $columnNode;
        }
        $xml .= "</table>" . "\n";
        $file = fopen('XML/text.xml', 'a');
        fwrite($file, $xml);
        fclose($file);

        $this->tableNode = null;
        $this->columnNodes = [];
        $this->tableComment = null;
    }

    public function closeXml()
    {
        $file = fopen('XML/text.xml', 'a');
        fwrite($file, '</schema>' . PHP_EOL);
        fclose($file);
    }
}