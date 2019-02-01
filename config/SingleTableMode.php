<?php

/**
 * Class SingleTableMode
 * return config for single table mode
 */
class SingleTableMode
{
    /**
     * const STRING contains SQL query for single table
     */
    const STRING = "CREATE TABLE `c38_us_cities` (
  `city` VARCHAR(255) NULL DEFAULT NULL,
  `city_ascii` VARCHAR(255) NULL DEFAULT NULL,
  `state_id` VARCHAR(255) NULL DEFAULT NULL,
  `state_name` VARCHAR(255) NULL DEFAULT NULL,
  `county_fips` INT(11) NULL DEFAULT NULL,
  `county_name` VARCHAR(255) NULL DEFAULT NULL,
  `lat` VARCHAR(255) NULL DEFAULT NULL,
  `lng` VARCHAR(255) NULL DEFAULT NULL,
  `population` VARCHAR(255) NULL DEFAULT NULL,
  `population_proper` VARCHAR(255) NULL DEFAULT NULL,
  `density` VARCHAR(255) NULL DEFAULT NULL,
  `source` VARCHAR(255) NULL DEFAULT NULL,
  `incorporated` VARCHAR(255) NULL DEFAULT NULL,
  `timezone` VARCHAR(255) NULL DEFAULT NULL,
  `zips` VARCHAR(32767) NULL DEFAULT NULL,
  `id` BIGINT(11) NULL DEFAULT NULL,
  INDEX `idx_c38_us_cities_city_state` (`city`, `state_name`)
) COLLATE='latin1_swedish_ci' ENGINE=InnoDB;";

    /**
     * @return array with query
     */
    public function getSingleTableModeConfig()
    {
        return [
          'string' => self::STRING
        ];
    }
}