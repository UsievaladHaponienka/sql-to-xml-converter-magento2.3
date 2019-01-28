<?php

/**
 * Class DatabaseMode
 * return config for database mode
 */

class DatabaseMode
{
    /**
     * @string Name of Table Schema
     */
    const TABLE_SCHEMA = "c38_january";

    /**
     * @string Table prefix
     */
    const TABLE_PREFIX = "c38\_%";

    /**
     * @return array with database mode config
     */
    public function getDatabaseModeConfig()
    {
        return [
          'table_schema' => self::TABLE_SCHEMA,
          'table_prefix' => self::TABLE_PREFIX
        ];
    }
}