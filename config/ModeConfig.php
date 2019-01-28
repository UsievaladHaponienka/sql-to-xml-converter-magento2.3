<?php

class ModeConfig
{
    /**
     * Mode can be either 'table' or 'database'
     */
    const MODE = 'database';

    public function getMode()
    {
        return [
            'mode' => self::MODE
        ];
    }
}