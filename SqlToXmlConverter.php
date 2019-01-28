<?php

define('ROOT', dirname(__FILE__));

include_once ROOT . '/Connection/Connection.php';
include_once ROOT . '/Helper/TypePrepareHelper.php';
include_once ROOT . '/Helper/KeyTypePrepareHelper.php';
include_once ROOT . '/Helper/AdditionalAttributeHelper.php';
include_once ROOT . '/XML/FullXml.php';
include_once ROOT . '/config/SingleTableMode.php';
include_once ROOT . '/config/DatabaseMode.php';
include_once ROOT . '/config/ModeConfig.php';

class SqlToXmlConverter
{
    const SINGLE_TABLE_MODE = 'table';

    const DATABASE_MODE = 'database';

    protected $singleTableModeObject;

    protected $databaseModeObject;

    protected $typePrepareHelper;

    protected $additionalAttributesHelper;

    protected $keyTypePrepareHelper;

    protected $fullXml;

    protected $connection;

    protected $tableName;

    public function __construct()
    {
        $this->singleTableModeObject = new SingleTableMode();
        $this->databaseModeObject = new DatabaseMode();
        $this->typePrepareHelper = new TypePrepareHelper();
        $this->additionalAttributesHelper = new AdditionalAttributesHelper();
        $this->keyTypePrepareHelper = new KeyTypePrepareHelper();
        $this->fullXml = new FullXml();
        $this->connection = new Connection();
    }

    public function prepareAndRunDatabaseMode()
    {
        $this->fullXml->removeFile();
        $this->fullXml->initXml();

        $databaseModeConfig = $this->databaseModeObject->getDatabaseModeConfig();

        $tableSchema = $databaseModeConfig['table_schema'];
        $tablePrefix = $databaseModeConfig['table_prefix'];

        $db = $this->connection->getConnection();
        $stmt = $db->prepare("SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES
             WHERE TABLE_NAME like \"$tablePrefix\" AND TABLE_SCHEMA = \"$tableSchema\"");
        $stmt->execute();
        $result = $stmt->fetchAll();

        foreach ($result as $hole) {
            $tableName = current($hole);
            $stmt2 = $db->prepare("SHOW CREATE TABLE {$tableName}");
            $stmt2->execute();
            $string = $stmt2->fetchColumn(1);
            if (!preg_match("~ALGORITHM~", $string)) {
                $this->runDatabaseMode($string);
            }
        }

        $this->fullXml->closeXml();
    }

    public function runDatabaseMode($string)
    {
        $lines = explode(PHP_EOL, $string);
        foreach ($lines as $lineNumber => $line) {
            if (preg_match("~ALGORITHM~", $line)) {
                continue;
            }
            switch (true) {
                case preg_match("~CREATE TABLE~", $line):
                    $this->convertToTableNode($line);
                    break;
                case preg_match("~ENGINE~", $line):
                    $this->getTableComment($line);
                    break;
                case preg_match("~KEY~", $line):
                    $this->convertToKeyNode($line);
                    break;
                default:
                    $this->convertToColumnNode($line);
                    break;
            }
        }
        $this->fullXml->writeXml();
    }

    public function runSingleTableMode()
    {
        $singleTableModeConfig = $this->singleTableModeObject->getSingleTableModeConfig();

        $string = $singleTableModeConfig['string'];

        $this->fullXml->removeFile();
        $this->fullXml->initXml();

        $lines = explode(PHP_EOL, $string);
        foreach ($lines as $lineNumber => $line) {
            if (preg_match("~ALGORITHM~", $line)) {
                continue;
            }
            switch (true) {
                case preg_match("~CREATE TABLE~", $line):
                    $this->convertToTableNode($line);
                    break;
                case preg_match("~ENGINE~", $line):
                    $this->getTableComment($line);
                    break;
                case preg_match("~KEY~", $line):
                    $this->convertToKeyNode($line);
                    break;
                default:
                    $this->convertToColumnNode($line);
                    break;
            }
        }
        $this->fullXml->writeXml();
        $this->fullXml->closeXml();
    }

    public function convertToTableNode($line)
    {
        preg_match("~CREATE TABLE `(.+)`~", $line, $match);
        $tableName = $match[1];
        $this->tableName = $tableName;
        $this->fullXml->setTableNode('<table name="' . $tableName . '" resource="default" engine="innodb"');
    }

    public function convertToColumnNode($line)
    {
        $node = '<column xsi:type="';
        $type = $this->getType($line);
        switch ($type) {
            case 'smallint':
                $node .= $this->typePrepareHelper->prepareSmallInt($line);
                break;
            case 'bigint':
                $node .= $this->typePrepareHelper->prepareBigInt($line);
                break;
            case 'tinyint':
                $node .= $this->typePrepareHelper->prepareTinyInt($line);
                break;
            case 'int':
                $node .= $this->typePrepareHelper->prepareInt($line);
                break;
            case 'decimal':
                $node .= $this->typePrepareHelper->prepareDecimal($line);
                break;
            case 'float':
                $node .= $this->typePrepareHelper->prepareFloat($line);
                break;
            case 'double':
                $node .= $this->typePrepareHelper->prepareDouble($line);
                break;
            case 'varchar':
                $node .= $this->typePrepareHelper->prepareVarchar($line);
                break;
            case 'boolean':
                $node .= $this->typePrepareHelper->prepareBoolean();
                break;
            case 'timestamp':
                $node .= $this->typePrepareHelper->prepareTimestamp($line);
                break;
            case 'date':
                $node .= 'date" ';
                break;
            case 'datetime':
                $node .= 'datetime" ';
                break;
            case 'blob':
                $node .= 'blob" ';
                break;
            case 'longblob':
                $node .= 'longblob" ';
                break;
            case 'mediumblog':
                $node .= 'mediumblog" ';
                break;
            case 'text':
                $node .= 'text" ';
                break;
            case 'longtext':
                $node .= 'longtext" ';
                break;
            case 'mediumtext':
                $node .= 'mediumtext" ';
                break;
        }
        $node .= $this->getColumnName($line);
        $node .= $this->additionalAttributesHelper->isUnsigned($line);
        $node .= $this->additionalAttributesHelper->isDefaultNull($line);
        $node .= $this->additionalAttributesHelper->isNotNull($line);
        $node .= $this->additionalAttributesHelper->isIdentity($line);
        $node .= $this->additionalAttributesHelper->isSetDefaultValue($line, $type);
        $node .= $this->additionalAttributesHelper->getComment($line);
        $node .= '/>' . "\n";
        $this->fullXml->addColumnNode($node);
    }

    public function convertToKeyNode($line)
    {
        $node = '';
        $keyType = $this->getKeyType($line);
        switch ($keyType) {
            case 'primary':
                $node .= $this->keyTypePrepareHelper->preparePrimaryKey($line);
                break;
            case 'index':
                $node .= $this->keyTypePrepareHelper->prepareIndexKey($line);
                break;
            case 'uniqie':
                $node .= $this->keyTypePrepareHelper->prepareUniqueKey($line);
                break;
            case 'foreign':
                $node .= $this->keyTypePrepareHelper->prepareForeignKey($line, $this->tableName);
                break;
        }
        $this->fullXml->addColumnNode($node);
    }

    public function getType($line)
    {
        $typeArray = $this->typePrepareHelper->getTypesArray();
        foreach ($typeArray as $pattern => $type) {
            if (preg_match("~$pattern~", $line)) {
                return $type;
            }
        }
        return null;
    }

    public function getKeyType($line)
    {
        $keyTypeArray = $this->keyTypePrepareHelper->getKeyTypesArray();
        foreach ($keyTypeArray as $pattern => $keyType) {
            if (preg_match("~$pattern~", $line)) {
                return $keyType;
            }
        }
        return null;
    }

    public function getColumnName($line)
    {
        preg_match("~`(.+)`~", $line, $match);
        return 'name="' . $match[1] . '" ';
    }

    public function getTableComment($line)
    {
        if (preg_match('~COMMENT=\'(.+)\'~', $line, $match)) {
            $this->fullXml->setTableComment(' comment="' . $match[1] . '">');
            return;
        }
        $this->fullXml->setTableComment('>');
        return;
    }
}

$modeConfig = new ModeConfig();
$sqlToXmlConverter = new SqlToXmlConverter();
$mode = $modeConfig->getMode();

switch ($mode) {
    case $sqlToXmlConverter::SINGLE_TABLE_MODE:
        $sqlToXmlConverter->runSingleTableMode();
        break;
    case $sqlToXmlConverter::DATABASE_MODE:
        $sqlToXmlConverter->prepareAndRunDatabaseMode();
        break;
}

