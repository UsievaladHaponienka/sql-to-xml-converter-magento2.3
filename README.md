# sql-to-xml-converter
This tool converts SQL-quieries to XML according to Declarative Schema in Magento 2.3.
Tool can either convert Single table or convert number of tables in database.

First you need to set mode. Go to config/ModeConfig.php and set const MODE to 'database' or 'table'.

1. Database Mode:
  1.1. Go to config/dbparams.php and set database connection parameters - host, username, database name and password.
  1.2. Go to config/DatabaseMode and set TABLE_SCHEMA and TABLE_PREFIX.
  
2. Single Table Mode:
  2.1. Go to config/SingleTableMode.php and set STRING - SQL-query. You can get this query with "SHOW CREATE TABLE `tablename`".
  
Now you can run file SqlToXmlConverter.php located in root directory.
Result can be found in XML/text.xml
