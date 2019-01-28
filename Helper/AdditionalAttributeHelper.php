<?php

class AdditionalAttributesHelper
{
    public function isUnsigned($line)
    {
        if (preg_match('~unsigned~', $line)) {
            return 'unsigned="true" ';
        }
        return '';
    }

    public function isDefaultNull($line)
    {
        if (preg_match('~DEFAULT NULL~', $line)) {
            return 'nullable="true" ';
        }
        return '';
    }

    public function isNotNull($line)
    {
        if (preg_match('~NOT NULL~', $line)) {
            return 'nullable="false" ';
        }
        return '';
    }

    public function isIdentity($line)
    {
        if (preg_match('~AUTO_INCREMENT~', $line)) {
            return 'identity="true" ';
        }
        return '';
    }

    public function isSetDefaultValue($line, $type)
    {
        if ($type == 'timestamp' AND preg_match("~CURRENT_TIMESTAMP~", $line)) {
            return 'default="CURRENT_TIMESTAMP" ';
        }

        if (!preg_match("~DEFAULT NULL~", $line) AND preg_match("~DEFAULT~", $line)) {
            preg_match("~DEFAULT '(.*)' ~", $line, $match);

            $defaultValue = $match[1] ?? null;
            if ($defaultValue !== null) {

                return 'default="' . $defaultValue . '" ';
            }
        }

        return '';
    }

    public function getComment($line)
    {
        if (preg_match("~COMMENT~", $line)) {
            preg_match("~COMMENT '(.+)'~", $line, $match);
            return 'comment="' . $match[1] . '" ';
        }
        return '';
    }
}