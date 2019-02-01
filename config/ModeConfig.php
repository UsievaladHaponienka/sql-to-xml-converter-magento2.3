<?php

class ModeConfig
{
    /**
     * Mode can be either 'table' or 'database'
     */
    const MODE = 'table';

    /**
     * @return string Mode
     */
    public function getMode()
    {
        return self::MODE;

    }
}