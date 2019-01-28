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
    const STRING = 'CREATE TABLE `c38_ad_landing_coupon` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT \'Id\',
  `email` varchar(128) NOT NULL DEFAULT \'0\' COMMENT \'Customer Email\',
  `landing_id` int(10) unsigned NOT NULL DEFAULT \'0\' COMMENT \'Ad Landing Id\',
  `coupon` varchar(64) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`,`landing_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2387 DEFAULT CHARSET=utf8 COMMENT=\'C38 Ad Landing Coupon\'';

    public function getSingleTableModeConfig()
    {
        return [
          'string' => self::STRING
        ];
    }
}